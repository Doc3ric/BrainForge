<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VocabularyCategory;
use Symfony\Component\Uid\Uuid;

class VocabularyCategoryFactory extends Factory
{
    protected $model = VocabularyCategory::class;

    public function definition(): array
    {
        return [
            'id' => (string) Uuid::v7(),
            'name' => $this->faker->unique()->word() . ' Category',
            'description' => $this->faker->sentence(),
        ];
    }
}
