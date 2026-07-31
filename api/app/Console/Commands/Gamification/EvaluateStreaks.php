<?php

namespace App\Console\Commands\Gamification;

use Illuminate\Console\Command;

class EvaluateStreaks extends Command
{
    protected $signature = 'gamification:evaluate-streaks';
    protected $description = 'Evaluate daily streaks for users who have crossed midnight in their timezone.';

    public function handle()
    {
        $this->info('Starting streak evaluation...');
        
        // Mocked implementation for Phase 3
        // Real implementation would:
        // 1. Find users whose timezone just crossed midnight.
        // 2. Check if they have an active XpLog for the preceding day.
        // 3. If no, check StreakRepository->getFreezeBalance()
        // 4. If freeze > 0, consume freeze. Else trigger StreakBroken event.
        
        $this->info('Streak evaluation completed.');
    }
}
