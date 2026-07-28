<?php

declare(strict_types=1);

namespace App\Domains\Authentication\DTOs;

use App\Domains\Authentication\ValueObjects\Email;

final readonly class ResetPasswordDTO
{
    public function __construct(
        public Email $email,
        public string $token,
        public string $password,
        public string $passwordConfirmation,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            email: Email::fromString($validated['email']),
            token: $validated['token'],
            password: $validated['password'],
            passwordConfirmation: $validated['password_confirmation'],
        );
    }
}
