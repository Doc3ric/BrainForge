<?php

namespace App\Http\Resources\Gamification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'xp_reward' => $this->xp_reward,
            'icon_path' => $this->icon_path,
            'is_unlocked' => $this->is_unlocked ?? false,
            'unlocked_at' => $this->unlocked_at ? clone clone $this->unlocked_at : null,
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
