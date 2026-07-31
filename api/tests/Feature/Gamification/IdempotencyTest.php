<?php

use App\Models\User;
use App\Models\XpActivityType;
use App\Services\Gamification\XpService;
use App\Services\Gamification\StreakService;
use App\Repositories\Gamification\XpRepository;
use App\Repositories\Gamification\StreakRepository;
use App\Services\Gamification\LevelService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents duplicate XP for exact same source', function () {
    $user = User::factory()->create();
    $activity = XpActivityType::create([
        'id' => \Symfony\Component\Uid\Uuid::v7(),
        'type_key' => 'test_activity',
        'display_name' => 'Test',
        'default_xp_amount' => 10
    ]);
    
    $xpRepo = new XpRepository();
    $levelService = new LevelService();
    $xpService = new XpService($xpRepo, $levelService);
    
    // Award first time
    $xpService->awardXp($user->id, 'test_activity', 'test_source', 'id_123');
    
    // Award second time (should be ignored)
    $xpService->awardXp($user->id, 'test_activity', 'test_source', 'id_123');
    
    expect(\App\Models\XpLog::count())->toBe(1);
});

it('prevents multiple streak increments in the same day timezone aware', function () {
    $user = User::factory()->create(['timezone' => 'Asia/Tokyo']);
    $streakRepo = new StreakRepository();
    $streakService = new StreakService($streakRepo);
    
    // Tokyo is +9. Let's say UTC is currently 23:00 on Monday.
    // Tokyo is 08:00 on Tuesday.
    Carbon::setTestNow('2026-07-27 23:00:00');
    
    $streakService->incrementStreak($user->id);
    expect($user->fresh()->current_streak)->toBe(1);
    
    // Call again on the same logical localized day
    $streakService->incrementStreak($user->id);
    expect($user->fresh()->current_streak)->toBe(1);
    
    // Move forward to Wednesday 01:00 Tokyo time (Tuesday 16:00 UTC)
    Carbon::setTestNow('2026-07-28 16:00:00');
    $streakService->incrementStreak($user->id);
    expect($user->fresh()->current_streak)->toBe(2);
});
