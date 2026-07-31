<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Symfony\Component\Uid\Uuid;

class UserVocabulary extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'user_vocabulary';

    protected $fillable = [
        'user_id',
        'vocabulary_word_id',
        'is_learned',
        'ease_factor',
        'interval_days',
        'repetition_count',
        'next_review_at',
        'last_interacted_at',
    ];

    protected $casts = [
        'is_learned' => 'boolean',
        'ease_factor' => 'decimal:2',
        'interval_days' => 'integer',
        'repetition_count' => 'integer',
        'next_review_at' => 'datetime',
        'last_interacted_at' => 'datetime',
    ];

    public function newUniqueId()
    {
        return (string) Uuid::v7();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function word()
    {
        return $this->belongsTo(VocabularyWord::class, 'vocabulary_word_id');
    }

    public function reviewLogs()
    {
        return $this->hasMany(VocabularyReviewLog::class, 'user_vocabulary_id');
    }
}
