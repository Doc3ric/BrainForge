<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

readonly class RegisterDTO
{
    public function __construct(
        public string $displayName,
        public string $email,
        public string $password
    ) {}
}
