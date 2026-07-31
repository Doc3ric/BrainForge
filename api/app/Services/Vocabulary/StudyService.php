<?php

namespace App\Services\Vocabulary;

use App\Models\UserVocabulary;
use App\Models\VocabularyStudySession;
use App\Repositories\Vocabulary\StudySessionRepository;
use App\Repositories\Vocabulary\VocabularyRepository;
use App\Events\Gamification\UserActivityCompleted;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Uuid;

class StudyService
{
    private StudySessionRepository $sessionRepository;
    private VocabularyRepository $vocabularyRepository;

    public function __construct(StudySessionRepository $sessionRepository, VocabularyRepository $vocabularyRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->vocabularyRepository = $vocabularyRepository;
    }

    public function startSession(string $userId): VocabularyStudySession
    {
        return $this->sessionRepository->createSession($userId);
    }

    public function completeSession(string $sessionId, string $userId, array $wordIds): void
    {
        $session = $this->sessionRepository->findById($sessionId);
        if (!$session || $session->user_id !== $userId) {
            throw new \InvalidArgumentException('Session not found or not owned by user.');
        }

        if ($session->status->value !== 'active') {
            throw new \InvalidArgumentException('Session is not active.');
        }

        $this->sessionRepository->completeSession($session, $wordIds);

        // Dispatch gamification event AFTER transaction (the repository method already wraps in transaction)
        event(new UserActivityCompleted(
            userId: $userId,
            activityType: 'vocab_study_session_completed',
            sourceType: 'vocabulary',
            sourceId: $session->id,
            metadata: ['word_count' => $session->word_count, 'goal_metric' => 'vocab']
        ));
    }

    public function markLearned(string $wordId, string $userId): UserVocabulary
    {
        $word = $this->vocabularyRepository->getById($wordId);
        if (!$word) {
            throw new \InvalidArgumentException('Word not found.');
        }

        $userVocab = DB::transaction(function () use ($wordId, $userId) {
            $uv = UserVocabulary::firstOrCreate(
                ['user_id' => $userId, 'vocabulary_word_id' => $wordId],
                [
                    'id' => (string) Uuid::v7(),
                    'is_learned' => false, // Will set to true below
                ]
            );

            if (!$uv->is_learned) {
                $uv->is_learned = true;
                $uv->ease_factor = 2.50;
                $uv->interval_days = 1;
                $uv->repetition_count = 0;
                $uv->next_review_at = now()->addDay();
                $uv->save();
                
                return $uv;
            }
            return null;
        });

        if ($userVocab) {
            // Dispatch event outside transaction
            event(new UserActivityCompleted(
                userId: $userId,
                activityType: 'vocab_learned',
                sourceType: 'vocabulary',
                sourceId: $userVocab->id,
                metadata: ['goal_metric' => 'vocab']
            ));
            return $userVocab;
        }
        
        return UserVocabulary::where('user_id', $userId)->where('vocabulary_word_id', $wordId)->first();
    }
}
