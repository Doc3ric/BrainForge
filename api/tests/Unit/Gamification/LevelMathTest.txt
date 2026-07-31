<?php

use App\Services\Gamification\LevelService;

it('calculates level 1 correctly for 0 XP', function () {
    $service = new LevelService();
    $result = $service->calculateLevelState(0);
    
    expect($result['current_level'])->toBe(1);
    expect($result['current_xp'])->toBe(0);
    expect($result['next_level_xp'])->toBe(100);
});

it('calculates level 2 correctly when passing threshold', function () {
    $service = new LevelService();
    $result = $service->calculateLevelState(150);
    
    // Level 1: 0-99. Level 2: 100-299.
    expect($result['current_level'])->toBe(2);
    expect($result['xp_into_current_level'])->toBe(50);
});
