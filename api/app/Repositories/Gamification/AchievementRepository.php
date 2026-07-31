<?php

namespace App\Repositories\Gamification;

use App\Models\Achievement;
use App\Models\UserAchievement;

class AchievementRepository
{
    public function getUnlockedAchievementIds(string $userId): array
    {
        return UserAchievement::where('user_id', $userId)->pluck('achievement_id')->toArray();
    }

    public function unlock(string $userId, string $achievementId): void
    {
        UserAchievement::firstOrCreate([
            'user_id' => $userId,
            'achievement_id' => $achievementId
        ]);
    }
    
    public function getAllAchievements(): \Illuminate\Database\Eloquent\Collection
    {
        return \Illuminate\Support\Facades\Cache::rememberForever('gamification.achievements', function () {
            return Achievement::all();
        });
    }
}
