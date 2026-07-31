<?php

namespace App\Enums;

enum VocabularyStudySessionStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case ABANDONED = 'abandoned';
}
