<?php

namespace App\Services\Gamification;

use App\Repositories\Gamification\AchievementRepository;
use App\Events\Gamification\AchievementUnlocked;
use App\Models\User;

class AchievementService
{
    private AchievementRepository $achievementRepo;

    public function __construct(AchievementRepository $achievementRepo)
    {
        $this->achievementRepo = $achievementRepo;
    }

    public function evaluateXpAchievements(string $userId, int $newTotalXp): void
    {
        $achievements = $this->achievementRepo->getAllAchievements()->where('condition_type', 'xp_total_reached');
        $unlocked = $this->achievementRepo->getUnlockedAchievementIds($userId);

        foreach ($achievements as $achievement) {
            if (!in_array($achievement->id, $unlocked) && $newTotalXp >= $achievement->condition_value) {
                $this->achievementRepo->unlock($userId, $achievement->id);
                event(new AchievementUnlocked($userId, $achievement->id, $achievement->key));
            }
        }
    }

    public function evaluateStreakAchievements(string $userId, int $newStreak): void
    {
        $achievements = $this->achievementRepo->getAllAchievements()->where('condition_type', 'streak_reached');
        $unlocked = $this->achievementRepo->getUnlockedAchievementIds($userId);

        foreach ($achievements as $achievement) {
            if (!in_array($achievement->id, $unlocked) && $newStreak >= $achievement->condition_value) {
                $this->achievementRepo->unlock($userId, $achievement->id);
                event(new AchievementUnlocked($userId, $achievement->id, $achievement->key));
            }
        }
    }
}
