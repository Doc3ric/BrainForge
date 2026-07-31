<?php

namespace App\DTOs\Vocabulary;

class ReviewRequestDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $userVocabularyId,
        public readonly int $qualityScore,
        public readonly string $idempotencyKey
    ) {}
}
