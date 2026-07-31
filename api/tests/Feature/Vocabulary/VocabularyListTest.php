<?php

namespace Tests\Feature\Vocabulary;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\VocabularyWord;
use App\Models\VocabularyCategory;

class VocabularyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_vocabulary_words()
    {
        $user = User::factory()->create();
        $cat = VocabularyCategory::factory()->create(['name' => 'Science']);
        VocabularyWord::factory()->count(3)->create(['category_id' => $cat->id]);

        $response = $this->actingAs($user)->getJson('/api/v1/vocabulary');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_filter_by_category()
    {
        $user = User::factory()->create();
        $cat1 = VocabularyCategory::factory()->create(['name' => 'Science']);
        $cat2 = VocabularyCategory::factory()->create(['name' => 'Art']);
        
        VocabularyWord::factory()->count(2)->create(['category_id' => $cat1->id]);
        VocabularyWord::factory()->count(3)->create(['category_id' => $cat2->id]);

        $response = $this->actingAs($user)->getJson('/api/v1/vocabulary?category_id=' . $cat1->id);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_search_vocabulary_words()
    {
        $user = User::factory()->create();
        $cat = VocabularyCategory::factory()->create();
        
        VocabularyWord::factory()->create(['category_id' => $cat->id, 'word' => 'apple']);
        VocabularyWord::factory()->create(['category_id' => $cat->id, 'word' => 'banana']);

        $response = $this->actingAs($user)->getJson('/api/v1/vocabulary?search=app');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.word', 'apple');
    }
}
