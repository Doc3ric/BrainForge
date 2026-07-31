<?php

namespace App\Policies\Vocabulary;

use App\Models\User;
use App\Models\VocabularyStudySession;

class StudySessionPolicy
{
    public function update(User $user, VocabularyStudySession $session): bool
    {
        return $user->id === $session->user_id;
    }
}
