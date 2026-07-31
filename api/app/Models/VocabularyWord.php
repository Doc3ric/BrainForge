<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Uid\Uuid;

class VocabularyWord extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'category_id',
        'difficulty_id',
        'word',
        'part_of_speech',
        'definition',
    ];

    public function newUniqueId()
    {
        return (string) Uuid::v7();
    }

    public function category()
    {
        return $this->belongsTo(VocabularyCategory::class, 'category_id');
    }

    public function difficulty()
    {
        return $this->belongsTo(DifficultyLevel::class, 'difficulty_id');
    }

    public function examples()
    {
        return $this->hasMany(VocabularyExample::class, 'vocabulary_word_id');
    }

    public function userVocabulary()
    {
        return $this->hasMany(UserVocabulary::class, 'vocabulary_word_id');
    }
}
