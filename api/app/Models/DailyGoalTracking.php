<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DailyGoalTracking extends Model {
    
    use HasUuids;

    protected $table = 'daily_goal_trackings';

    protected $fillable = [
        'user_id', 'goal_date', 'target_vocab', 'target_quizzes', 'target_xp',
        'current_vocab', 'current_quizzes', 'current_xp', 'is_completed'
    ];

    protected function casts(): array
    {
        return [
            'goal_date' => 'date',
            'is_completed' => 'boolean',
        ];
    }
    
    public function newUniqueId(): string
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

}
