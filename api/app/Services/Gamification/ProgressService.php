<?php

namespace App\Services\Gamification;

class ProgressService
{
    public function getVocabularyProgress(string $userId): array
    {
        $totalWords = \App\Models\VocabularyWord::count();
        
        $learnedWords = \App\Models\UserVocabulary::where('user_id', $userId)
            ->where('is_learned', true)
            ->count();
            
        $reviewsPending = \App\Models\UserVocabulary::where('user_id', $userId)
            ->where('is_learned', true)
            ->where('next_review_at', '<=', now())
            ->count();
            
        return [
            'total_words' => $totalWords,
            'learned_words' => $learnedWords,
            'reviews_pending' => $reviewsPending,
        ];
    }
}
