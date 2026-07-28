<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Listeners;

use App\Domains\Authentication\Contracts\LoginActivityRepositoryInterface;
use App\Domains\Authentication\Events\PasswordChanged;
use App\Domains\Authentication\Models\LoginActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LogPasswordChange implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly LoginActivityRepositoryInterface $loginActivityRepository,
    ) {}

    public function handle(PasswordChanged $event): void
    {
        $this->loginActivityRepository->create([
            'user_id' => $event->userId,
            'ip_address' => $event->ipAddress ?? request()->ip() ?? '0.0.0.0',
            'user_agent' => request()->userAgent() ?? 'Unknown',
            'event_type' => LoginActivity::EVENT_PASSWORD_CHANGE,
            'successful' => true,
        ]);
    }
}
