<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Exceptions;

final class AccountLockedException extends AuthenticationException
{
    public function __construct(public readonly int $minutesRemaining = 15)
    {
        parent::__construct(
            "Account temporarily locked. Please try again in {$minutesRemaining} minutes.",
            429,
        );
    }
}
