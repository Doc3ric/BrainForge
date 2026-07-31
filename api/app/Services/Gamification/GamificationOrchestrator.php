<?php

namespace App\Services\Gamification;

use App\Events\Gamification\UserActivityCompleted;
use App\Events\Gamification\XPAwarded;
use App\Events\Gamification\StreakIncremented;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GamificationOrchestrator
{
    private XpService $xpService;
    private DailyGoalService $goalService;
    private StreakService $streakService;

    public function __construct(XpService $xpService, DailyGoalService $goalService, StreakService $streakService)
    {
        $this->xpService = $xpService;
        $this->goalService = $goalService;
        $this->streakService = $streakService;
    }

    public function handleUserActivity(UserActivityCompleted $event): void
    {
        Log::info('Gamification: UserActivityCompleted', (array) $event);

        DB::transaction(function () use ($event) {
            // 1. Award XP
            $this->xpService->awardXp($event->userId, $event->activityType, $event->sourceType, $event->sourceId);
            
            // 2. Increment Daily Goals (assuming event metadata maps to metric types like vocab or quiz)
            if (isset($event->metadata['goal_metric'])) {
                $this->goalService->incrementGoal($event->userId, $event->metadata['goal_metric'], 1);
            }

            // 3. Increment Streak (simplistic increment logic for infrastructure)
            $this->streakService->incrementStreak($event->userId);
        });
    }

    public function handleXpAwarded(XPAwarded $event): void
    {
        app(AchievementService::class)->evaluateXpAchievements($event->userId, $event->newTotalXp);
        
        // Also update the DailyGoal XP metric
        $this->goalService->incrementGoal($event->userId, 'xp', $event->amount);
    }
    
    public function handleStreakIncremented(StreakIncremented $event): void
    {
        app(AchievementService::class)->evaluateStreakAchievements($event->userId, $event->newStreak);
    }
}
