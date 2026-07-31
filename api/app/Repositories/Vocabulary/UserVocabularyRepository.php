<?php

namespace App\Repositories\Vocabulary;

use App\Models\UserVocabulary;
use Illuminate\Database\Eloquent\Collection;

class UserVocabularyRepository
{
    public function findByWordId(string $userId, string $wordId): ?UserVocabulary
    {
        return UserVocabulary::where('user_id', $userId)
            ->where('vocabulary_word_id', $wordId)
            ->first();
    }

    /**
     * Locks the UserVocabulary record for update.
     * This MUST be executed inside the same DB::transaction() as the subsequent
     * save/log operations to ensure concurrency safety and prevent race conditions
     * on review submissions.
     */
    public function lockForUpdate(string $id): ?UserVocabulary
    {
        return UserVocabulary::lockForUpdate()->find($id);
    }

    public function getReviewQueue(string $userId, int $limit): Collection
    {
        return UserVocabulary::with(['word', 'word.category', 'word.difficulty'])
            ->where('user_id', $userId)
            ->where('is_learned', true)
            ->where('next_review_at', '<=', now())
            ->orderBy('next_review_at', 'asc')
            ->orderBy('id', 'asc') // Stabilize ordering
            ->limit($limit)
            ->get();
    }

    public function save(UserVocabulary $userVocabulary): UserVocabulary
    {
        $userVocabulary->save();
        return $userVocabulary;
    }
}
