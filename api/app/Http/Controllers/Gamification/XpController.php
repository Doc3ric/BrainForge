<?php

namespace App\Http\Controllers\Gamification;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gamification\XpLogResource;
use App\Models\XpLog;
use Illuminate\Http\Request;

class XpController extends Controller
{
    public function index(Request $request)
    {
        $logs = XpLog::with('activityType')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return XpLogResource::collection($logs);
    }
}
