<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Uuid;

class XpActivityTypeSeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            ['type_key' => 'vocab_learned', 'display_name' => 'Vocabulary Word Learned', 'default_xp_amount' => 10],
            ['type_key' => 'vocab_review_passed', 'display_name' => 'Vocabulary Review Passed', 'default_xp_amount' => 5],
            ['type_key' => 'vocab_study_session_completed', 'display_name' => 'Study Session Completed', 'default_xp_amount' => 20],
            ['type_key' => 'quiz_completed', 'display_name' => 'Quiz Completed', 'default_xp_amount' => 30],
        ];

        foreach ($activities as $activity) {
            DB::table('xp_activity_types')->updateOrInsert(
                ['type_key' => $activity['type_key']],
                [
                    'id' => (string) Uuid::v7(),
                    'display_name' => $activity['display_name'],
                    'default_xp_amount' => $activity['default_xp_amount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
