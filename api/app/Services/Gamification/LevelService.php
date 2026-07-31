<?php

namespace App\Services\Gamification;

class LevelService
{
    // A simple math formula for XP: next_level_xp = level * 100
    // Total XP to reach Level N = 50 * (N * (N - 1))
    
    public function calculateLevelState(int $totalXp): array
    {
        // Simple quadratic inversion for demonstration
        $level = floor((1 + sqrt(1 + 8 * $totalXp / 100)) / 2);
        $level = max(1, (int) $level);
        
        $xpForCurrentLevel = 50 * ($level * ($level - 1));
        $xpForNextLevel = 50 * (($level + 1) * $level);
        
        $xpIntoCurrentLevel = $totalXp - $xpForCurrentLevel;
        $xpNeededForNextLevel = $xpForNextLevel - $xpForCurrentLevel;
        
        $percent = $xpNeededForNextLevel > 0 
            ? round(($xpIntoCurrentLevel / $xpNeededForNextLevel) * 100) 
            : 100;
            
        return [
            'current_level' => $level,
            'current_xp' => $totalXp,
            'next_level_xp' => $xpForNextLevel,
            'xp_into_current_level' => $xpIntoCurrentLevel,
            'xp_needed_for_next_level' => $xpNeededForNextLevel,
            'level_progress_percent' => $percent,
        ];
    }
}
