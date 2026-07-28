<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Events;

use Illuminate\Foundation\Events\Dispatchable;

final class PasswordResetCompleted
{
    use Dispatchable;

    public function __construct(
        public readonly int $userId,
    ) {}
}
