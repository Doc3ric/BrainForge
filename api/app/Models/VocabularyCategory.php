<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Uid\Uuid;

class VocabularyCategory extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function newUniqueId()
    {
        return (string) Uuid::v7();
    }

    public function words()
    {
        return $this->hasMany(VocabularyWord::class, 'category_id');
    }
}
