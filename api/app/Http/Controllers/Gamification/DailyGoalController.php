<?php

namespace App\Http\Controllers\Gamification;

use App\Http\Controllers\Controller;
use App\Models\DailyGoalTracking;
use App\Http\Resources\Gamification\DailyGoalResource;
use App\Repositories\Gamification\DailyGoalRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyGoalController extends Controller
{
    private DailyGoalRepository $repository;

    public function __construct(DailyGoalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $date = Carbon::now($user->timezone ?? 'UTC')->toDateString();
        
        $goal = $this->repository->findForDate($user->id, $date);

        // If no goal tracked for today yet, return a mock default state based on settings
        if (!$goal) {
            $goal = new DailyGoalTracking([
                'id' => \Symfony\Component\Uid\Uuid::v7(),
                'goal_date' => $date,
                'target_vocab' => $user->daily_target_vocab,
                'target_quizzes' => $user->daily_target_quizzes,
                'target_xp' => $user->daily_target_xp,
                'current_vocab' => 0,
                'current_quizzes' => 0,
                'current_xp' => 0,
                'is_completed' => false,
            ]);
        }
        
        return new DailyGoalResource($goal);
    }
}
