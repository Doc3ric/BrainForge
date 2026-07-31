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

        // Vocabulary Routes
        Route::prefix('vocabulary')->group(function () {
            Route::get('/', [\App\Http\Controllers\Vocabulary\VocabularyController::class, 'index']);
            Route::get('/categories', [\App\Http\Controllers\Vocabulary\VocabularyController::class, 'categories']);
            Route::get('/progress', [\App\Http\Controllers\Vocabulary\VocabularyProgressController::class, 'index']);
            Route::get('/reviews', [\App\Http\Controllers\Vocabulary\ReviewController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Vocabulary\VocabularyController::class, 'show']);
            
            Route::post('/{id}/learn', [\App\Http\Controllers\Vocabulary\StudyController::class, 'learn']);
            Route::post('/{id}/reviews', [\App\Http\Controllers\Vocabulary\ReviewController::class, 'store']);
            
            Route::post('/study-sessions', [\App\Http\Controllers\Vocabulary\StudyController::class, 'startSession']);
            Route::patch('/study-sessions/{id}', [\App\Http\Controllers\Vocabulary\StudyController::class, 'completeSession']);
        });
    });
});
