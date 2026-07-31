<?php

namespace App\Services\Gamification;

use App\Repositories\Gamification\DailyGoalRepository;
use App\Models\User;
use App\Events\Gamification\DailyGoalProgressUpdated;
use App\Events\Gamification\DailyGoalCompleted;
use Carbon\Carbon;

class DailyGoalService
{
    private DailyGoalRepository $goalRepo;

    public function __construct(DailyGoalRepository $goalRepo)
    {
        $this->goalRepo = $goalRepo;
    }

    public function incrementGoal(string $userId, string $metricType, int $amount = 1): void
    {
        $user = User::find($userId);
        if (!$user) return;

        $date = Carbon::now($user->timezone ?? 'UTC')->toDateString();
        
        $goal = $this->goalRepo->getOrCreateForDateLocked(
            $userId, 
            $date, 
            $user->daily_target_vocab, 
            $user->daily_target_quizzes, 
            $user->daily_target_xp
        );

        $wasCompleted = $goal->is_completed;
        $column = "current_{$metricType}";
        
        if (!array_key_exists($column, $goal->getAttributes())) {
            return;
        }

        $goal->{$column} += $amount;
        $targetColumn = "target_{$metricType}";

        event(new DailyGoalProgressUpdated($userId, $date, $metricType, $goal->{$column}, $goal->{$targetColumn}));

        $goal->is_completed = ($goal->current_vocab >= $goal->target_vocab) && 
                              ($goal->current_quizzes >= $goal->target_quizzes) && 
                              ($goal->current_xp >= $goal->target_xp);
        
        $goal->save();

        if (!$wasCompleted && $goal->is_completed) {
            event(new DailyGoalCompleted($userId, $date));
        }
    }
}
