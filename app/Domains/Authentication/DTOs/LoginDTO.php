<?php

declare(strict_types=1);

namespace App\Domains\Authentication\DTOs;

use App\Domains\Authentication\ValueObjects\Email;
use App\Domains\Authentication\ValueObjects\IpAddress;

final readonly class LoginDTO
{
    public function __construct(
        public Email $email,
        public string $password,
        public IpAddress $ipAddress,
        public string $userAgent,
        public bool $rememberMe = false,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            email: Email::fromString($validated['email']),
            password: $validated['password'],
            ipAddress: IpAddress::fromRequest(),
            userAgent: request()->userAgent() ?? 'Unknown',
            rememberMe: $validated['remember'] ?? false,
        );
    }
}
