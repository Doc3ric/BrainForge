<?php

namespace App\Events\Gamification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StreakBroken {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly string $userId;
    public readonly int $lostStreak;

    public function __construct(string $userId, int $lostStreak)
    {
        $this->userId = $userId;
        $this->lostStreak = $lostStreak;
    }
}
