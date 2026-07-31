<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StreakFreezeLog extends Model {
    
    use HasUuids;

    protected $fillable = ['user_id', 'action_type', 'reason'];
    
    public function newUniqueId(): string
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

}
