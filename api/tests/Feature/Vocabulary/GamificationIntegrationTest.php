<?php

namespace Tests\Feature\Vocabulary;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\VocabularyStudySession;
use App\Models\VocabularyWord;
use App\Models\DailyGoalTracking;
use App\Models\XpLog;
use App\Enums\VocabularyStudySessionStatus;
use Illuminate\Support\Facades\DB;

class GamificationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed needed gamification tables
        $this->artisan('db:seed', ['--class' => 'XpActivityTypeSeeder']);
    }

    public function test_completing_session_with_5_words_increments_streak_and_xp()
    {
        $user = User::factory()->create();
        
        $session = VocabularyStudySession::factory()->create([
            'user_id' => $user->id,
            'status' => VocabularyStudySessionStatus::ACTIVE->value,
        ]);
        
        $words = VocabularyWord::factory()->count(5)->create();

        // Ensure we are dispatching real events, not faked
        $response = $this->actingAs($user)->patchJson('/api/v1/vocabulary/study-sessions/' . $session->id, [
            'status' => 'completed',
            'word_ids' => $words->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);

        // Assert XP Log exists
        $this->assertDatabaseHas('xp_logs', [
            'user_id' => $user->id,
            'source_type' => 'vocabulary',
            'source_id' => $session->id,
        ]);

        // Assert Daily Goal incremented
        $this->assertDatabaseHas('daily_goal_trackings', [
            'user_id' => $user->id,
            'current_vocab' => 1,
        ]);

        // Assert Streak incremented
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'current_streak' => 1,
        ]);
    }

    public function test_completing_session_with_fewer_than_5_words_does_not_increment_streak()
    {
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

        // Daily goal should increment
        $this->assertDatabaseHas('daily_goal_trackings', [
            'user_id' => $user->id,
            'current_vocab' => 1,
        ]);

        // Streak should NOT increment because word_count < 5
        $user->refresh();
        $this->assertEquals(0, $user->current_streak);
    }
}
