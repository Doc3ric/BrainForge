<?php

namespace App\Repositories\Gamification;

use App\Models\User;
use App\Models\XpLog;

class XpRepository
{
    /**
     * Lock the user record for update to prevent concurrent XP race conditions.
     */
    public function lockUserForUpdate(string $userId): ?User
    {
        return User::lockForUpdate()->find($userId);
    }

    public function createLog(string $userId, string $activityTypeId, int $amount, string $sourceType = null, string $sourceId = null): ?XpLog
    {
        // Require sourceType and sourceId to enforce idempotency
        if (!$sourceType || !$sourceId) {
            return XpLog::create([
                'user_id' => $userId,
                'activity_type_id' => $activityTypeId,
                'amount' => $amount,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
            ]);
        }

        $log = XpLog::firstOrCreate([
            'user_id' => $userId,
            'activity_type_id' => $activityTypeId,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ], [
            'amount' => $amount,
        ]);

        return $log->wasRecentlyCreated ? $log : null;
    }
}
