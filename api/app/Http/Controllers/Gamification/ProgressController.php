<?php

namespace App\Http\Controllers\Gamification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        // Mocked for Phase 3 - will aggregate XpLogs in Phase 4
        return response()->json([
            'data' => [
                'total_xp' => $request->user()->total_xp,
                'level' => $request->user()->level,
                'history' => []
            ]
        ]);
    }
}
