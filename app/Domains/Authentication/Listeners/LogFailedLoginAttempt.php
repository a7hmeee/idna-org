<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Listeners;

use App\Domains\Authentication\Contracts\LoginActivityRepositoryInterface;
use App\Domains\Authentication\Events\LoginAttemptFailed;
use App\Domains\Authentication\Models\LoginActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LogFailedLoginAttempt implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly LoginActivityRepositoryInterface $loginActivityRepository,
    ) {}

    public function handle(LoginAttemptFailed $event): void
    {
        $this->loginActivityRepository->create([
            'user_id' => $event->userId,
            'ip_address' => (string) $event->ipAddress,
            'user_agent' => $event->userAgent,
            'event_type' => LoginActivity::EVENT_FAILED,
            'successful' => false,
            'failure_reason' => $event->reason,
        ]);
    }
}
