<?php

namespace App\Services\Gamification;

use App\Repositories\Gamification\StreakRepository;
use App\Events\Gamification\StreakIncremented;

class StreakService
{
    private StreakRepository $streakRepo;

    public function __construct(StreakRepository $streakRepo)
    {
        $this->streakRepo = $streakRepo;
    }

    public function incrementStreak(string $userId): void
    {
        $user = $this->streakRepo->lockUserForUpdate($userId);
        if (!$user) return;

        $today = \Carbon\Carbon::now($user->timezone ?? 'UTC')->startOfDay();
        
        if ($user->last_streak_increment_at && $user->last_streak_increment_at->equalTo($today)) {
            // Idempotency: streak already incremented today
            return;
        }

        $user->current_streak += 1;
        if ($user->current_streak > $user->longest_streak) {
            $user->longest_streak = $user->current_streak;
        }
        $user->last_streak_increment_at = $today;
        $user->save();

        event(new StreakIncremented($userId, $user->current_streak));
    }
}
