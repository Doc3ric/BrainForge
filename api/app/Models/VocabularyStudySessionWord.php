<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Symfony\Component\Uid\Uuid;

class VocabularyStudySessionWord extends Pivot
{
    use HasUuids;

    protected $table = 'vocabulary_study_session_words';

    public $timestamps = false;

    protected $fillable = [
        'study_session_id',
        'vocabulary_word_id',
        'studied_at',
    ];

    protected $casts = [
        'studied_at' => 'datetime',
    ];

    public function newUniqueId()
    {
        return (string) Uuid::v7();
    }
}
