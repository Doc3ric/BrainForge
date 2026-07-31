<?php

namespace App\Models;

use App\Enums\VocabularyStudySessionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Symfony\Component\Uid\Uuid;

class VocabularyStudySession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'status',
        'started_at',
        'completed_at',
        'word_count',
    ];

    protected $casts = [
        'status' => VocabularyStudySessionStatus::class,
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'word_count' => 'integer',
    ];

    public function newUniqueId()
    {
        return (string) Uuid::v7();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function words()
    {
        return $this->belongsToMany(VocabularyWord::class, 'vocabulary_study_session_words', 'study_session_id', 'vocabulary_word_id')
            ->using(VocabularyStudySessionWord::class)
            ->withPivot('studied_at', 'id');
    }
}
