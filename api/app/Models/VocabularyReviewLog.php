<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Symfony\Component\Uid\Uuid;

class VocabularyReviewLog extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_vocabulary_id',
        'idempotency_key',
        'quality_score',
        'old_interval_days',
        'new_interval_days',
        'old_ease_factor',
        'new_ease_factor',
        'old_repetition_count',
        'new_repetition_count',
        'reviewed_at',
    ];

    public $timestamps = false; // We use reviewed_at instead of created_at/updated_at

    protected $casts = [
        'quality_score' => 'integer',
        'old_interval_days' => 'integer',
        'new_interval_days' => 'integer',
        'old_ease_factor' => 'decimal:2',
        'new_ease_factor' => 'decimal:2',
        'old_repetition_count' => 'integer',
        'new_repetition_count' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function newUniqueId()
    {
        return (string) Uuid::v7();
    }

    public function userVocabulary()
    {
        return $this->belongsTo(UserVocabulary::class, 'user_vocabulary_id');
    }
}
