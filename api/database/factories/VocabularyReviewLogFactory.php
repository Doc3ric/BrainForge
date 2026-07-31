<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VocabularyReviewLog;
use App\Models\UserVocabulary;
use Symfony\Component\Uid\Uuid;

class VocabularyReviewLogFactory extends Factory
{
    protected $model = VocabularyReviewLog::class;

    public function definition(): array
    {
        return [
            'id' => (string) Uuid::v7(),
            'user_vocabulary_id' => UserVocabulary::factory(),
            'idempotency_key' => (string) Uuid::v7(),
            'quality_score' => $this->faker->numberBetween(0, 5),
            'old_interval_days' => 1,
            'new_interval_days' => 6,
            'old_ease_factor' => 2.50,
            'new_ease_factor' => 2.60,
            'old_repetition_count' => 0,
            'new_repetition_count' => 1,
            'reviewed_at' => now(),
        ];
    }
}
