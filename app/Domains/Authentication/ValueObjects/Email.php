<?php

declare(strict_types=1);

namespace App\Domains\Authentication\ValueObjects;

use App\Domains\Authentication\Exceptions\AuthenticationException;

final readonly class Email
{
    private function __construct(
        private string $value,
    ) {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new AuthenticationException("Invalid email address: {$value}");
        }
    }

    public static function fromString(string $email): self
    {
        return new self(mb_strtolower(trim($email)));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
