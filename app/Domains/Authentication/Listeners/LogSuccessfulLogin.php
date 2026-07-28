<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Listeners;

use App\Domains\Authentication\Contracts\LoginActivityRepositoryInterface;
use App\Domains\Authentication\Contracts\UserRepositoryInterface;
use App\Domains\Authentication\Events\UserLoggedIn;
use App\Domains\Authentication\Models\LoginActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LogSuccessfulLogin implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly LoginActivityRepositoryInterface $loginActivityRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function handle(UserLoggedIn $event): void
    {
        $this->loginActivityRepository->create([
            'user_id' => $event->userId,
            'ip_address' => (string) $event->ipAddress,
            'user_agent' => $event->userAgent,
            'event_type' => LoginActivity::EVENT_LOGIN,
            'successful' => true,
            'session_id' => $event->sessionId,
        ]);

        $this->userRepository->updateLastLogin($event->userId);
        $this->userRepository->resetLoginAttempts($event->userId);
    }
}
