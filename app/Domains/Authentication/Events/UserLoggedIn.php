<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Events;

use App\Domains\Authentication\ValueObjects\IpAddress;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UserLoggedIn
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly int $userId,
        public readonly IpAddress $ipAddress,
        public readonly string $userAgent,
        public readonly ?string $sessionId = null,
        public readonly bool $rememberMe = false,
    ) {}
}
