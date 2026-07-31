<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Achievement extends Model {
    
    use HasUuids;

    protected $fillable = ['key', 'name', 'description', 'condition_type', 'condition_value', 'xp_reward', 'icon_path'];
    
    public function newUniqueId(): string
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

}
