<?php

declare(strict_types=1);

namespace App\Domains\Authentication\DTOs;

use App\Domains\Authentication\ValueObjects\Email;

final readonly class ForgotPasswordDTO
{
    public function __construct(
        public Email $email,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            email: Email::fromString($validated['email']),
        );
    }
}
