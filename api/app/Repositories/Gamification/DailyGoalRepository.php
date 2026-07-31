<?php

namespace App\Repositories\Gamification;

use App\Models\DailyGoalTracking;
use Carbon\Carbon;

class DailyGoalRepository
{
    public function getOrCreateForDateLocked(string $userId, string $date, int $targetVocab, int $targetQuizzes, int $targetXp): DailyGoalTracking
    {
        return DailyGoalTracking::lockForUpdate()->firstOrCreate(
            ['user_id' => $userId, 'goal_date' => Carbon::parse($date)->startOfDay()],
            [
                'target_vocab' => $targetVocab,
                'target_quizzes' => $targetQuizzes,
                'target_xp' => $targetXp,
            ]
        );
    }

    public function findForDate(string $userId, string $date): ?DailyGoalTracking
    {
        return DailyGoalTracking::where('user_id', $userId)
            ->where('goal_date', Carbon::parse($date)->startOfDay())
            ->first();
    }
}
