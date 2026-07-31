<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Symfony\Component\Uid\Uuid;

class VocabularyExample extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'vocabulary_word_id',
        'example_sentence',
    ];

    public function newUniqueId()
    {
        return (string) Uuid::v7();
    }

    public function word()
    {
        return $this->belongsTo(VocabularyWord::class, 'vocabulary_word_id');
    }
}
