<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VocabularyExample;
use App\Models\VocabularyWord;
use Symfony\Component\Uid\Uuid;

class VocabularyExampleFactory extends Factory
{
    protected $model = VocabularyExample::class;

    public function definition(): array
    {
        return [
            'id' => (string) Uuid::v7(),
            'vocabulary_word_id' => VocabularyWord::factory(),
            'example_sentence' => $this->faker->sentence(),
        ];
    }
}
