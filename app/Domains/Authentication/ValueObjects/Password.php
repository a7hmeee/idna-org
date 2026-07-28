<?php

declare(strict_types=1);

namespace App\Domains\Authentication\ValueObjects;

use App\Domains\Authentication\Exceptions\AuthenticationException;

final readonly class Password
{
    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 64;

    private function __construct(
        private string $hashedValue,
    ) {}

    public static function fromPlainText(string $plainText): self
    {
        $trimmed = trim($plainText);

        if (mb_strlen($trimmed) < self::MIN_LENGTH) {
            throw new AuthenticationException(
                "Password must be at least " . self::MIN_LENGTH . " characters long."
            );
        }

        if (mb_strlen($trimmed) > self::MAX_LENGTH) {
            throw new AuthenticationException(
                "Password must not exceed " . self::MAX_LENGTH . " characters."
            );
        }

        if (! preg_match('/[A-Z]/', $trimmed)) {
            throw new AuthenticationException('Password must contain at least one uppercase letter.');
        }

        if (! preg_match('/[a-z]/', $trimmed)) {
            throw new AuthenticationException('Password must contain at least one lowercase letter.');
        }

        if (! preg_match('/[0-9]/', $trimmed)) {
            throw new AuthenticationException('Password must contain at least one digit.');
        }

        if (! preg_match('/[!@#$%^&*(),.?":{}|<>_\-]/', $trimmed)) {
            throw new AuthenticationException('Password must contain at least one special character.');
        }

        return new self(bcrypt($trimmed));
    }

    public static function fromHash(string $hash): self
    {
        return new self($hash);
    }

    public function hash(): string
    {
        return $this->hashedValue;
    }

    public function verify(string $plainText): bool
    {
        return password_verify($plainText, $this->hashedValue);
    }

    public function __toString(): string
    {
        return $this->hashedValue;
    }
}
