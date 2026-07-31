<?php

namespace Tests\Feature\Vocabulary;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserVocabulary;
use Illuminate\Support\Facades\Event;
use App\Events\Gamification\UserActivityCompleted;
use Symfony\Component\Uid\Uuid;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_review()
    {
        Event::fake();
        $user = User::factory()->create();
        $userVocab = UserVocabulary::factory()->create([
            'user_id' => $user->id,
            'is_learned' => true,
        ]);

        $idempotencyKey = (string) Uuid::v7();

        $response = $this->actingAs($user)->postJson('/api/v1/vocabulary/' . $userVocab->id . '/reviews', [
            'quality_score' => 4,
            'idempotency_key' => $idempotencyKey,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('vocabulary_review_logs', [
            'idempotency_key' => $idempotencyKey,
            'quality_score' => 4,
        ]);

        Event::assertDispatched(UserActivityCompleted::class, function ($event) {
            return $event->activityType === 'vocab_review_passed';
        });
    }

    public function test_duplicate_idempotency_key_is_ignored()
    {
        Event::fake();
        $user = User::factory()->create();
        $userVocab = UserVocabulary::factory()->create([
            'user_id' => $user->id,
            'is_learned' => true,
        ]);

        $idempotencyKey = (string) Uuid::v7();

        $this->actingAs($user)->postJson('/api/v1/vocabulary/' . $userVocab->id . '/reviews', [
            'quality_score' => 4,
            'idempotency_key' => $idempotencyKey,
        ]);

        // Second request with same key
        $response = $this->actingAs($user)->postJson('/api/v1/vocabulary/' . $userVocab->id . '/reviews', [
            'quality_score' => 5, // different score
            'idempotency_key' => $idempotencyKey, // same key
        ]);

        $response->assertStatus(200);

        // Should only have 1 log with quality 4
        $this->assertDatabaseMissing('vocabulary_review_logs', [
            'quality_score' => 5,
            'idempotency_key' => $idempotencyKey,
        ]);

        Event::assertDispatchedTimes(UserActivityCompleted::class, 1);
    }
}
