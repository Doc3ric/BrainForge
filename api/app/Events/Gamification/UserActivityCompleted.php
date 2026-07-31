<?php

namespace App\Events\Gamification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivityCompleted {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly string $userId;
    public readonly string $activityType;
    public readonly string $sourceType;
    public readonly ?string $sourceId;
    public readonly string $occurredAt;
    public readonly array $metadata;

    public function __construct(
        string $userId,
        string $activityType,
        string $sourceType,
        ?string $sourceId = null,
        array $metadata = []
    ) {
        $this->userId = $userId;
        $this->activityType = $activityType;
        $this->sourceType = $sourceType;
        $this->sourceId = $sourceId;
        $this->occurredAt = now()->toIso8601String();
        $this->metadata = $metadata;
    }
}
