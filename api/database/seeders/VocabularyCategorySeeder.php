<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VocabularyCategory;
use Symfony\Component\Uid\Uuid;

class VocabularyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Academic', 'description' => 'Words used in academic and scholarly contexts'],
            ['name' => 'Business', 'description' => 'Professional and corporate vocabulary'],
            ['name' => 'Travel', 'description' => 'Words for travel, tourism, and geography'],
            ['name' => 'Science', 'description' => 'Scientific terms and concepts'],
            ['name' => 'Culture & Arts', 'description' => 'Cultural expressions, idioms, and arts'],
            ['name' => 'Technology', 'description' => 'Modern technology and digital vocabulary'],
        ];

        foreach ($categories as $cat) {
            VocabularyCategory::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'id' => (string) Uuid::v7(),
                    'description' => $cat['description'],
                ]
            );
        }
    }
}
