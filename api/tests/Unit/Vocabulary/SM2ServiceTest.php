<?php

namespace Tests\Unit\Vocabulary;

use PHPUnit\Framework\TestCase;
use App\Services\Vocabulary\SM2Service;

class SM2ServiceTest extends TestCase
{
    private SM2Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SM2Service();
    }

    public function test_quality_below_3_resets_interval_and_repetitions()
    {
        $result = $this->service->calculate(
            qualityScore: 2,
            currentEaseFactor: 2.50,
            currentIntervalDays: 10,
            currentRepetitionCount: 3
        );

        $this->assertEquals(1, $result->intervalDays);
        $this->assertEquals(2.50, $result->easeFactor);
        $this->assertEquals(0, $result->repetitionCount);
    }

    public function test_quality_3_keeps_state_unchanged()
    {
        $result = $this->service->calculate(
            qualityScore: 3,
            currentEaseFactor: 2.50,
            currentIntervalDays: 10,
            currentRepetitionCount: 3
        );

        $this->assertEquals(10, $result->intervalDays);
        $this->assertEquals(2.50, $result->easeFactor);
        $this->assertEquals(3, $result->repetitionCount);
    }

    public function test_quality_4_increases_interval_and_modifies_ease_factor()
    {
        $result = $this->service->calculate(
            qualityScore: 4,
            currentEaseFactor: 2.50,
            currentIntervalDays: 6,
            currentRepetitionCount: 1
        );

        // Quality 4, repetition 1 -> next interval should be 6 according to logic, but wait:
        // "IF repetition_count == 1: interval_days = 6"
        // Then repetitionCount increments to 2
        $this->assertEquals(6, $result->intervalDays);
        $this->assertEquals(2, $result->repetitionCount);
        
        // Ease factor calculation: 2.50 + (0.1 - (5-4) * (0.08 + (5-4)*0.02))
        // 2.50 + (0.1 - 1 * (0.10)) = 2.50
        $this->assertEquals(2.50, round($result->easeFactor, 2));
    }

    public function test_quality_5_increases_ease_factor()
    {
        $result = $this->service->calculate(
            qualityScore: 5,
            currentEaseFactor: 2.50,
            currentIntervalDays: 6,
            currentRepetitionCount: 2
        );

        // interval = round(6 * 2.50) = 15
        $this->assertEquals(15, $result->intervalDays);
        $this->assertEquals(3, $result->repetitionCount);
        
        // Ease factor: 2.50 + (0.1 - 0) = 2.60
        $this->assertEquals(2.60, round($result->easeFactor, 2));
    }
}
