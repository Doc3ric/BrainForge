<?php

namespace App\Http\Resources\Gamification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyGoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'goal_date' => $this->goal_date->toDateString(),
            'target_vocab' => $this->target_vocab,
            'target_quizzes' => $this->target_quizzes,
            'target_xp' => $this->target_xp,
            'current_vocab' => $this->current_vocab,
            'current_quizzes' => $this->current_quizzes,
            'current_xp' => $this->current_xp,
            'is_completed' => $this->is_completed,
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'timestamp' => now()->toIso8601String(),
            ],
            'links' => [
                'self' => $request->url(),
            ],
        ];
    }
}
