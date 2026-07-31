<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserAchievement extends Model {
    
    use HasUuids;
    
    public $timestamps = false;
    protected $fillable = ['user_id', 'achievement_id', 'unlocked_at'];

    protected function casts(): array
    {
        return [
            'unlocked_at' => 'datetime',
        ];
    }
    
    public function newUniqueId(): string
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

}
