<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function registerUser(RegisterDTO $dto): User
    {
        // Creating the user; password is automatically hashed by the User model's casts
        $user = new User();
        $user->display_name = $dto->displayName;
        $user->email = $dto->email;
        $user->password = Hash::make($dto->password); // explicitly hashing though casts can do it
        $user->save();
        
        return $user;
    }

    public function attemptLogin(LoginDTO $dto): bool
    {
        if (! Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }
        
        return true;
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }
}
