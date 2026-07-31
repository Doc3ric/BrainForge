<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserVocabulary;
use App\Models\User;
use App\Models\VocabularyWord;
use Symfony\Component\Uid\Uuid;

class UserVocabularyFactory extends Factory
{
    protected $model = UserVocabulary::class;

    public function definition(): array
    {
        return [
            'id' => (string) Uuid::v7(),
            'user_id' => User::factory(),
            'vocabulary_word_id' => VocabularyWord::factory(),
            'is_learned' => false,
            'ease_factor' => 2.50,
            'interval_days' => 1,
            'repetition_count' => 0,
            'next_review_at' => null,
            'last_interacted_at' => null,
        ];
    }
}
