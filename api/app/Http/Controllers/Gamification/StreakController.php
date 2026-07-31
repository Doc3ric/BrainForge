<?php

namespace App\Http\Controllers\Gamification;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gamification\StreakResource;
use App\Repositories\Gamification\StreakRepository;
use Illuminate\Http\Request;

class StreakController extends Controller
{
    private StreakRepository $streakRepo;

    public function __construct(StreakRepository $streakRepo)
    {
        $this->streakRepo = $streakRepo;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Compute transient freeze_balance for the resource
        $user->freeze_balance = $this->streakRepo->getFreezeBalance($user->id);

        return new StreakResource($user);
    }
}
