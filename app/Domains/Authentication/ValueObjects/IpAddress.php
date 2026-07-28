<?php

declare(strict_types=1);

namespace App\Domains\Authentication\ValueObjects;

use App\Domains\Authentication\Exceptions\AuthenticationException;

final readonly class IpAddress
{
    private function __construct(
        private string $value,
    ) {
        if (! filter_var($value, FILTER_VALIDATE_IP)) {
            throw new AuthenticationException("Invalid IP address: {$value}");
        }
    }

    public static function fromString(string $ip): self
    {
        return new self(trim($ip));
    }

    public static function fromRequest(): self
    {
        return new self(request()->ip());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isPrivate(): bool
    {
        return ! filter_var($this->value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
