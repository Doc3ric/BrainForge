<?php

namespace App\Http\Resources\Vocabulary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVocabularyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_learned' => $this->is_learned,
            'ease_factor' => (float) $this->ease_factor,
            'interval_days' => $this->interval_days,
            'repetition_count' => $this->repetition_count,
            'next_review_at' => $this->next_review_at?->toIso8601String(),
            'last_interacted_at' => $this->last_interacted_at?->toIso8601String(),
        ];
    }
}
