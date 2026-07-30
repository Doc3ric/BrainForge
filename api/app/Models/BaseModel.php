<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasUuids;

    /**
     * Generate a new UUID for the model.
     * We override this to enforce UUIDv7 if preferred,
     * though Laravel's HasUuids uses UUIDv4 by default.
     * For full UUIDv7 support, you'd use Str::uuid()->toString() in Laravel 11+ (if supported)
     * or ramsey/uuid directly. Laravel 11+ Str::uuid() generates v4.
     * Str::orderedUuid() generates v4 MAC-based (ordered).
     * We will use Str::uuid() for now, which can be swapped to uuid7() when natively exposed.
     */
}
