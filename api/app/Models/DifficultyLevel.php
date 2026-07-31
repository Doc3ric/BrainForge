<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DifficultyLevel extends Model {
    
    use HasUuids;

    protected $fillable = ['level_key', 'display_name', 'order_index'];
    
    public function newUniqueId(): string
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

}
