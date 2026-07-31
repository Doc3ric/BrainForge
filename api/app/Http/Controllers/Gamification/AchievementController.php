<?php

namespace App\Http\Controllers\Gamification;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gamification\AchievementResource;
use App\Repositories\Gamification\AchievementRepository;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    private AchievementRepository $achievementRepo;

    public function __construct(AchievementRepository $achievementRepo)
    {
        $this->achievementRepo = $achievementRepo;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        $allAchievements = $this->achievementRepo->getAllAchievements();
        $unlockedIds = $this->achievementRepo->getUnlockedAchievementIds($user->id);
        
        $allAchievements->transform(function ($achievement) use ($unlockedIds) {
            $achievement->is_unlocked = in_array($achievement->id, $unlockedIds);
            // In a real query we'd JOIN user_achievements to get unlocked_at
            return $achievement;
        });
        
        return AchievementResource::collection($allAchievements);
    }
}
