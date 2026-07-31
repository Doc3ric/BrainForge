<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VocabularyWord;
use App\Models\VocabularyCategory;
use App\Models\DifficultyLevel;
use Symfony\Component\Uid\Uuid;

class VocabularyWordFactory extends Factory
{
    protected $model = VocabularyWord::class;

    public function definition(): array
    {
        return [
            'id' => (string) Uuid::v7(),
            'category_id' => VocabularyCategory::factory(),
            'difficulty_id' => DifficultyLevel::factory(),
            'word' => $this->faker->word(),
            'part_of_speech' => $this->faker->randomElement(['noun', 'verb', 'adjective', 'adverb', 'phrasal_verb']),
            'definition' => $this->faker->sentence(),
        ];
    }
}
