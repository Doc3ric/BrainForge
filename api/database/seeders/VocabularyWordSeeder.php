<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VocabularyWord;
use App\Models\VocabularyCategory;
use App\Models\DifficultyLevel;
use App\Models\VocabularyExample;
use Illuminate\Support\Facades\DB;

class VocabularyWordSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed if empty to prevent timeouts on re-seeding
        if (VocabularyWord::count() > 0) {
            return;
        }

        $categories = VocabularyCategory::all();
        $difficulties = DifficultyLevel::all();

        if ($categories->isEmpty() || $difficulties->isEmpty()) {
            return;
        }

        // We need 500 words. We'll insert in chunks to save memory and time
        // The factory handles assigning random category and difficulty if not overridden,
        // but it's faster to pass them explicitly or let the factory pick from DB.
        
        // Disable query log to save memory
        DB::connection()->disableQueryLog();

        for ($i = 0; $i < 10; $i++) {
            VocabularyWord::factory()->count(50)->create()->each(function ($word) {
                // At least 2 examples per word
                VocabularyExample::factory()->count(2)->create([
                    'vocabulary_word_id' => $word->id,
                ]);
            });
        }
    }
}
