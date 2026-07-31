<?php

namespace App\Repositories\Vocabulary;

use App\Models\VocabularyWord;
use App\Models\VocabularyCategory;
use Illuminate\Pagination\LengthAwarePaginator;

class VocabularyRepository
{
    public function getCategories()
    {
        return VocabularyCategory::orderBy('name')->get();
    }

    public function getPaginatedList(string $userId, array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = VocabularyWord::query()
            ->with(['category', 'difficulty'])
            ->with(['userVocabulary' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['difficulty_id'])) {
            $query->where('difficulty_id', $filters['difficulty_id']);
        }

        if (!empty($filters['search'])) {
            $operator = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where('word', $operator, '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'learned') {
                $query->whereHas('userVocabulary', function ($q) use ($userId) {
                    $q->where('user_id', $userId)->where('is_learned', true);
                });
            } elseif ($filters['status'] === 'unlearned') {
                $query->whereDoesntHave('userVocabulary', function ($q) use ($userId) {
                    $q->where('user_id', $userId)->where('is_learned', true);
                });
            }
        }

        return $query->orderBy('word')->paginate($perPage);
    }

    public function getDetail(string $wordId, string $userId): ?VocabularyWord
    {
        return VocabularyWord::with(['category', 'difficulty', 'examples'])
            ->with(['userVocabulary' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->find($wordId);
    }

    public function getById(string $wordId): ?VocabularyWord
    {
        return VocabularyWord::find($wordId);
    }
}
