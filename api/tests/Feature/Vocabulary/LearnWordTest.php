<?php

namespace Tests\Feature\Vocabulary;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\VocabularyWord;
use App\Models\UserVocabulary;
use Illuminate\Support\Facades\Event;
use App\Events\Gamification\UserActivityCompleted;

class LearnWordTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_mark_word_as_learned()
    {
        Event::fake();
        $user = User::factory()->create();
        $word = VocabularyWord::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/vocabulary/' . $word->id . '/learn');

        $response->assertStatus(201)
            ->assertJsonPath('data.is_learned', true);

        $this->assertDatabaseHas('user_vocabulary', [
            'user_id' => $user->id,
            'vocabulary_word_id' => $word->id,
            'is_learned' => true,
        ]);

        Event::assertDispatched(UserActivityCompleted::class, function ($event) {
            return $event->activityType === 'vocab_learned';
        });
    }

    public function test_duplicate_learn_is_idempotent()
    {
        Event::fake();
        $user = User::factory()->create();
        $word = VocabularyWord::factory()->create();

        UserVocabulary::factory()->create([
            'user_id' => $user->id,
            'vocabulary_word_id' => $word->id,
            'is_learned' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/vocabulary/' . $word->id . '/learn');

        $response->assertStatus(200)
            ->assertJsonPath('data.is_learned', true);

        // Should not dispatch event again
        Event::assertNotDispatched(UserActivityCompleted::class);
    }
}
