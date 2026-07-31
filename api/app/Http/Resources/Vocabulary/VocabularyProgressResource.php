<?php

namespace App\Http\Resources\Vocabulary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VocabularyProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_words' => $this['total_words'] ?? 0,
            'learned_words' => $this['learned_words'] ?? 0,
            'reviews_pending' => $this['reviews_pending'] ?? 0,
        ];
    }
}
