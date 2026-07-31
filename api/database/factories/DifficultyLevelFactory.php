<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DifficultyLevel;
use Symfony\Component\Uid\Uuid;

class DifficultyLevelFactory extends Factory
{
    protected $model = DifficultyLevel::class;

    public function definition(): array
    {
        return [
            'id' => (string) Uuid::v7(),
            'level_key' => 'level_' . $this->faker->unique()->numberBetween(1, 100),
            'display_name' => 'Level ' . $this->faker->numberBetween(1, 100),
            'order_index' => $this->faker->unique()->numberBetween(1, 100),
        ];
    }
}
