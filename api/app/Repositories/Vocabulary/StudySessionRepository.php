<?php

namespace App\Repositories\Vocabulary;

use App\Models\VocabularyStudySession;
use App\Models\VocabularyStudySessionWord;
use App\Enums\VocabularyStudySessionStatus;
use Illuminate\Support\Facades\DB;

class StudySessionRepository
{
    public function createSession(string $userId): VocabularyStudySession
    {
        return VocabularyStudySession::create([
            'user_id' => $userId,
            'status' => VocabularyStudySessionStatus::ACTIVE->value,
            'started_at' => now(),
            'word_count' => 0,
        ]);
    }

    public function findById(string $id): ?VocabularyStudySession
    {
        return VocabularyStudySession::find($id);
    }

    public function lockForUpdate(string $id): ?VocabularyStudySession
    {
        return VocabularyStudySession::lockForUpdate()->find($id);
    }

    public function completeSession(VocabularyStudySession $session, array $wordIds): void
    {
        DB::transaction(function () use ($session, $wordIds) {
            $session->status = VocabularyStudySessionStatus::COMPLETED->value;
            $session->completed_at = now();
            
            // Count unique words to prevent duplicate counts
            $uniqueWordIds = array_unique($wordIds);
            $session->word_count = count($uniqueWordIds);
            $session->save();

            // Insert pivot records
            $pivotData = array_map(function ($wordId) use ($session) {
                return [
                    'id' => (string) \Symfony\Component\Uid\Uuid::v7(),
                    'study_session_id' => $session->id,
                    'vocabulary_word_id' => $wordId,
                    'studied_at' => now(),
                ];
            }, $wordIds);

            VocabularyStudySessionWord::insert($pivotData);
        });
    }
}
