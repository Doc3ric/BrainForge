<?php

namespace Tests\Feature\Vocabulary;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\VocabularyStudySession;
use App\Models\VocabularyWord;
use Illuminate\Support\Facades\Event;
use App\Events\Gamification\UserActivityCompleted;
use App\Enums\VocabularyStudySessionStatus;

class StudySessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_start_session()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/vocabulary/study-sessions');

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'active');
            
        $this->assertDatabaseHas('vocabulary_study_sessions', [
            'user_id' => $user->id,
            'status' => 'active',
        ]);
    }

    public function test_completing_session_with_fewer_than_5_words_dispatches_event()
    {
        Event::fake();
        $user = User::factory()->create();
        $session = VocabularyStudySession::factory()->create([
            'user_id' => $user->id,
            'status' => VocabularyStudySessionStatus::ACTIVE->value,
        ]);
        
        $words = VocabularyWord::factory()->count(3)->create();

        $response = $this->actingAs($user)->patchJson('/api/v1/vocabulary/study-sessions/' . $session->id, [
            'status' => 'completed',
            'word_ids' => $words->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('vocabulary_study_sessions', [
            'id' => $session->id,
            'status' => 'completed',
            'word_count' => 3,
        ]);

        // Even with < 5 words, the event is dispatched. The Orchestrator handles the streak logic.
        Event::assertDispatched(UserActivityCompleted::class, function ($event) {
            return $event->activityType === 'vocab_study_session_completed' &&
                   $event->metadata['word_count'] === 3;
        });
    }

    public function test_completing_session_with_5_or_more_words_dispatches_event()
    {
        Event::fake();
        $user = User::factory()->create();
        $session = VocabularyStudySession::factory()->create([
            'user_id' => $user->id,
            'status' => VocabularyStudySessionStatus::ACTIVE->value,
        ]);
        
        $words = VocabularyWord::factory()->count(5)->create();

        $response = $this->actingAs($user)->patchJson('/api/v1/vocabulary/study-sessions/' . $session->id, [
            'status' => 'completed',
            'word_ids' => $words->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);

        Event::assertDispatched(UserActivityCompleted::class, function ($event) {
            return $event->activityType === 'vocab_study_session_completed' &&
                   $event->metadata['word_count'] === 5;
        });
    }
}
