<?php

namespace App\DTOs\Vocabulary;

class SM2ResultDTO
{
    public function __construct(
        public readonly int $intervalDays,
        public readonly float $easeFactor,
        public readonly int $repetitionCount
    ) {}
}
