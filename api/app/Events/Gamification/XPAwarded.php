<?php

namespace App\Events\Gamification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class XPAwarded {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly string $userId;
    public readonly int $amount;
    public readonly string $activityType;
    public readonly int $newTotalXp;

    public function __construct(string $userId, int $amount, string $activityType, int $newTotalXp)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->activityType = $activityType;
        $this->newTotalXp = $newTotalXp;
    }
}
