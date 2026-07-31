<?php

namespace App\Services\Vocabulary;

use App\DTOs\Vocabulary\ReviewRequestDTO;
use App\Models\VocabularyReviewLog;
use App\Repositories\Vocabulary\UserVocabularyRepository;
use App\Events\Gamification\UserActivityCompleted;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Uuid;

class ReviewService
{
    private UserVocabularyRepository $repository;
    private SM2Service $sm2Service;

    public function __construct(UserVocabularyRepository $repository, SM2Service $sm2Service)
    {
        $this->repository = $repository;
        $this->sm2Service = $sm2Service;
    }

    public function getReviewQueue(string $userId, int $limit = 50)
    {
        return $this->repository->getReviewQueue($userId, $limit);
    }

    public function submitReview(ReviewRequestDTO $dto): void
    {
        // Check idempotency first before starting transaction
        $existingLog = VocabularyReviewLog::where('idempotency_key', $dto->idempotencyKey)->first();
        if ($existingLog) {
            return; // Already processed
        }

        $result = DB::transaction(function () use ($dto) {
            $userVocab = $this->repository->lockForUpdate($dto->userVocabularyId);
            
            if (!$userVocab || $userVocab->user_id !== $dto->userId) {
                throw new \InvalidArgumentException('User vocabulary record not found or not owned by user.');
            }

            if (!$userVocab->is_learned) {
                throw new \InvalidArgumentException('Word has not been learned yet.');
            }

            $oldInterval = $userVocab->interval_days;
            $oldEase = $userVocab->ease_factor;
            $oldRepetitions = $userVocab->repetition_count;

            $sm2Result = $this->sm2Service->calculate(
                $dto->qualityScore,
                $oldEase,
                $oldInterval,
                $oldRepetitions
            );

            $userVocab->interval_days = $sm2Result->intervalDays;
            $userVocab->ease_factor = $sm2Result->easeFactor;
            $userVocab->repetition_count = $sm2Result->repetitionCount;
            $userVocab->next_review_at = now()->addDays($sm2Result->intervalDays);
            $userVocab->last_interacted_at = now();
            
            $this->repository->save($userVocab);

            VocabularyReviewLog::create([
                'id' => (string) Uuid::v7(),
                'user_vocabulary_id' => $userVocab->id,
                'idempotency_key' => $dto->idempotencyKey,
                'quality_score' => $dto->qualityScore,
                'old_interval_days' => $oldInterval,
                'new_interval_days' => $sm2Result->intervalDays,
                'old_ease_factor' => $oldEase,
                'new_ease_factor' => $sm2Result->easeFactor,
                'old_repetition_count' => $oldRepetitions,
                'new_repetition_count' => $sm2Result->repetitionCount,
                'reviewed_at' => now(),
            ]);

            return $userVocab;
        });

        // Gamification dispatch after successful transaction commit
        if ($dto->qualityScore >= 3) {
            event(new UserActivityCompleted(
                userId: $dto->userId,
                activityType: 'vocab_review_passed',
                sourceType: 'vocabulary',
                sourceId: $result->id
            ));
        }
    }
}
