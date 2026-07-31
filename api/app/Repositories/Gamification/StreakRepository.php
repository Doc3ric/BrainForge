<?php

namespace App\Repositories\Gamification;

use App\Models\User;
use App\Models\StreakFreezeLog;

class StreakRepository
{
    public function lockUserForUpdate(string $userId): ?User
    {
        return User::lockForUpdate()->find($userId);
    }

    public function getFreezeBalance(string $userId): int
    {
        $grants = StreakFreezeLog::where('user_id', $userId)->where('action_type', 'grant')->count();
        $consumptions = StreakFreezeLog::where('user_id', $userId)->where('action_type', 'consume')->count();
        
        return max(0, $grants - $consumptions);
    }

    public function consumeFreeze(string $userId, string $reason = null): void
    {
        StreakFreezeLog::create([
            'user_id' => $userId,
            'action_type' => 'consume',
            'reason' => $reason,
        ]);
    }
}
