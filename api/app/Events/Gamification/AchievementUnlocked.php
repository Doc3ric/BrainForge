<?php

namespace App\Events\Gamification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly string $userId;
    public readonly string $achievementId;
    public readonly string $achievementKey;

    public function __construct(string $userId, string $achievementId, string $achievementKey)
    {
        $this->userId = $userId;
        $this->achievementId = $achievementId;
        $this->achievementKey = $achievementKey;
    }
}
