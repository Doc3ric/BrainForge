<?php

namespace App\Http\Resources\Gamification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StreakResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'current_streak' => $this->current_streak,
            'longest_streak' => $this->longest_streak,
            'freeze_balance' => $this->freeze_balance ?? 0,
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
