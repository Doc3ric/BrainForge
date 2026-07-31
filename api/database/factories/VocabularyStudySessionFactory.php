<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VocabularyStudySession;
use App\Models\User;
use App\Enums\VocabularyStudySessionStatus;
use Symfony\Component\Uid\Uuid;

class VocabularyStudySessionFactory extends Factory
{
    protected $model = VocabularyStudySession::class;

    public function definition(): array
    {
        return [
            'id' => (string) Uuid::v7(),
            'user_id' => User::factory(),
            'status' => VocabularyStudySessionStatus::ACTIVE->value,
            'started_at' => now(),
            'completed_at' => null,
            'word_count' => 0,
        ];
    }
}
