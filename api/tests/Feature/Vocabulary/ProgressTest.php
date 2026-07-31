<?php

namespace Tests\Feature\Vocabulary;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\VocabularyWord;
use App\Models\UserVocabulary;

class ProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_progress()
    {
        $user = User::factory()->create();
        
        VocabularyWord::factory()->count(10)->create();
        
        $words = VocabularyWord::take(3)->get();
        foreach ($words as $word) {
            UserVocabulary::factory()->create([
                'user_id' => $user->id,
                'vocabulary_word_id' => $word->id,
                'is_learned' => true,
                'next_review_at' => now()->subDay(), // pending review
            ]);
        }

        $response = $this->actingAs($user)->getJson('/api/v1/vocabulary/progress');

        $response->assertStatus(200)
            ->assertJsonPath('data.total_words', 10)
            ->assertJsonPath('data.learned_words', 3)
            ->assertJsonPath('data.reviews_pending', 3);
    }
}
