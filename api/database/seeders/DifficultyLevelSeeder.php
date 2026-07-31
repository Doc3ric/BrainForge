<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Uuid;

class DifficultyLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['level_key' => 'beginner', 'display_name' => 'Beginner', 'order_index' => 1],
            ['level_key' => 'elementary', 'display_name' => 'Elementary', 'order_index' => 2],
            ['level_key' => 'intermediate', 'display_name' => 'Intermediate', 'order_index' => 3],
            ['level_key' => 'upper_intermediate', 'display_name' => 'Upper Intermediate', 'order_index' => 4],
            ['level_key' => 'advanced', 'display_name' => 'Advanced', 'order_index' => 5],
            ['level_key' => 'proficient', 'display_name' => 'Proficient', 'order_index' => 6],
        ];

        foreach ($levels as $level) {
            DB::table('difficulty_levels')->updateOrInsert(
                ['level_key' => $level['level_key']],
                [
                    'id' => (string) Uuid::v7(),
                    'display_name' => $level['display_name'],
                    'order_index' => $level['order_index'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
