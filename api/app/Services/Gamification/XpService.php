<?php

namespace App\Services\Gamification;

use App\Repositories\Gamification\XpRepository;
use App\Models\XpActivityType;
use App\Events\Gamification\XPAwarded;
use App\Events\Gamification\LevelReached;
use Illuminate\Support\Facades\Cache;

class XpService
{
    private XpRepository $xpRepo;
    private LevelService $levelService;

    public function __construct(XpRepository $xpRepo, LevelService $levelService)
    {
        $this->xpRepo = $xpRepo;
        $this->levelService = $levelService;
    }

    public function awardXp(string $userId, string $activityTypeKey, string $sourceType = null, string $sourceId = null): void
    {
        $activityType = Cache::rememberForever("xp_activity_{$activityTypeKey}", function () use ($activityTypeKey) {
            return XpActivityType::where('type_key', $activityTypeKey)->first();
        });

        if (!$activityType) {
            return;
        }

        $user = $this->xpRepo->lockUserForUpdate($userId);
        if (!$user) return;

        $oldLevel = $this->levelService->calculateLevelState($user->total_xp)['current_level'];

        $log = $this->xpRepo->createLog($userId, $activityType->id, $activityType->default_xp_amount, $sourceType, $sourceId);
        
        if (!$log) {
            // Idempotency: exact same XP already awarded
            return;
        }

        $user->total_xp += $activityType->default_xp_amount;
        $user->save();

        $newState = $this->levelService->calculateLevelState($user->total_xp);
        $newLevel = $newState['current_level'];

        event(new XPAwarded($userId, $activityType->default_xp_amount, $activityTypeKey, $user->total_xp));

        if ($newLevel > $oldLevel) {
            $user->level = $newLevel;
            $user->save();
            event(new LevelReached($userId, $newLevel, $user->total_xp));
        }
    }
}
