<?php

namespace App\Events\Gamification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DailyGoalProgressUpdated {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly string $userId;
    public readonly string $goalDate;
    public readonly string $metricType; // 'vocab', 'quiz', 'xp'
    public readonly int $currentValue;
    public readonly int $targetValue;

    public function __construct(string $userId, string $goalDate, string $metricType, int $currentValue, int $targetValue)
    {
        $this->userId = $userId;
        $this->goalDate = $goalDate;
        $this->metricType = $metricType;
        $this->currentValue = $currentValue;
        $this->targetValue = $targetValue;
    }
}
