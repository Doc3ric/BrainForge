<?php

namespace Tests\Feature\Vocabulary;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\VocabularyWord;
use App\Models\UserVocabulary;
use Symfony\Component\Uid\Uuid;

class WordDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_word_detail()
    {
        $user = User::factory()->create();
        $word = VocabularyWord::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/vocabulary/' . $word->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $word->id)
            ->assertJsonPath('data.word', $word->word);
    }

    public function test_word_detail_includes_user_state()
    {
        $user = User::factory()->create();
        $word = VocabularyWord::factory()->create();
        
        UserVocabulary::factory()->create([
            'user_id' => $user->id,
            'vocabulary_word_id' => $word->id,
            'is_learned' => true,
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/vocabulary/' . $word->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.user_state.is_learned', true);
    }
}
