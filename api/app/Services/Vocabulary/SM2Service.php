<?php

namespace App\Services\Vocabulary;

use App\DTOs\Vocabulary\SM2ResultDTO;

class SM2Service
{
    /**
     * Calculates the next SM-2 algorithm state.
     */
    public function calculate(int $qualityScore, float $currentEaseFactor, int $currentIntervalDays, int $currentRepetitionCount): SM2ResultDTO
    {
        // Guard against invalid quality score
        if ($qualityScore < 0 || $qualityScore > 5) {
            throw new \InvalidArgumentException('Quality score must be between 0 and 5');
        }

        if ($qualityScore < 3) {
            // Failed recall: reset repetitions and interval, keep ease factor unchanged
            return new SM2ResultDTO(1, $currentEaseFactor, 0);
        }

        if ($qualityScore === 3) {
            // Borderline recall: keep everything exactly as is, just step forward by current interval
            return new SM2ResultDTO($currentIntervalDays, $currentEaseFactor, $currentRepetitionCount);
        }

        // Quality >= 4 (good recall)
        if ($currentRepetitionCount === 0) {
            $intervalDays = 1;
        } elseif ($currentRepetitionCount === 1) {
            $intervalDays = 6;
        } else {
            $intervalDays = (int) round($currentIntervalDays * $currentEaseFactor);
        }

        // Calculate new ease factor
        $newEaseFactor = $currentEaseFactor + (0.1 - (5 - $qualityScore) * (0.08 + (5 - $qualityScore) * 0.02));
        $newEaseFactor = max(1.30, $newEaseFactor);

        return new SM2ResultDTO($intervalDays, $newEaseFactor, $currentRepetitionCount + 1);
    }
}
