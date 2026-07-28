<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Events;

use App\Domains\Authentication\ValueObjects\IpAddress;
use Illuminate\Foundation\Events\Dispatchable;

final class LoginAttemptFailed
{
    use Dispatchable;

    public function __construct(
        public readonly string $email,
        public readonly IpAddress $ipAddress,
        public readonly string $userAgent,
        public readonly string $reason,
        public readonly ?int $userId = null,
    ) {}
}
