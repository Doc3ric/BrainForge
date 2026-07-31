<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['display_name', 'email', 'password', 'timezone', 'theme', 'total_xp', 'level', 'current_streak', 'longest_streak', 'daily_target_vocab', 'daily_target_quizzes', 'daily_target_xp', 'last_streak_increment_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids;

    /**
     * Generate a new UUIDv7 for the model.
     */
    public function newUniqueId(): string
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_streak_increment_at' => 'date',
        ];
    }
}
