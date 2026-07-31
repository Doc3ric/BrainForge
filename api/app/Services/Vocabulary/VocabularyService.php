<?php

namespace App\Services\Vocabulary;

use App\Repositories\Vocabulary\VocabularyRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class VocabularyService
{
    private VocabularyRepository $repository;

    public function __construct(VocabularyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCategories()
    {
        return $this->repository->getCategories();
    }

    public function getPaginatedList(string $userId, array $filters): LengthAwarePaginator
    {
        return $this->repository->getPaginatedList($userId, $filters);
    }

    public function getDetail(string $wordId, string $userId)
    {
        return $this->repository->getDetail($wordId, $userId);
    }
}
