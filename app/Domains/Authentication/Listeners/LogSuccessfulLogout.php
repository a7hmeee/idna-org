<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Listeners;

use App\Domains\Authentication\Contracts\LoginActivityRepositoryInterface;
use App\Domains\Authentication\Events\UserLoggedOut;
use App\Domains\Authentication\Models\LoginActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LogSuccessfulLogout implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly LoginActivityRepositoryInterface $loginActivityRepository,
    ) {}

    public function handle(UserLoggedOut $event): void
    {
        $this->loginActivityRepository->create([
            'user_id' => $event->userId,
            'ip_address' => request()->ip() ?? '0.0.0.0',
            'user_agent' => request()->userAgent() ?? 'Unknown',
            'event_type' => LoginActivity::EVENT_LOGOUT,
            'successful' => true,
            'session_id' => $event->sessionId,
        ]);
    }
}
