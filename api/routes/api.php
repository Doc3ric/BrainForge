<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])
            ->middleware('throttle:auth_endpoints');
            
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:auth_endpoints');
            
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

    // Gamification Routes (Authenticated)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('daily-goals', [\App\Http\Controllers\Gamification\DailyGoalController::class, 'index']);
        Route::get('streaks', [\App\Http\Controllers\Gamification\StreakController::class, 'index']);
        Route::get('achievements', [\App\Http\Controllers\Gamification\AchievementController::class, 'index']);
        Route::get('progress', [\App\Http\Controllers\Gamification\ProgressController::class, 'index']);
        Route::get('xp/history', [\App\Http\Controllers\Gamification\XpController::class, 'index']);
    });
});
