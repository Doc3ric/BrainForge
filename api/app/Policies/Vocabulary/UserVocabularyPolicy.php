<?php

namespace App\Policies\Vocabulary;

use App\Models\User;
use App\Models\UserVocabulary;

class UserVocabularyPolicy
{
    public function update(User $user, UserVocabulary $userVocabulary): bool
    {
        return $user->id === $userVocabulary->user_id;
    }
}
