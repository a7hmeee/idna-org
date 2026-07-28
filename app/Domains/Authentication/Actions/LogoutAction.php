<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Events\UserLoggedOut;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final class LogoutAction
{
    public function execute(): void
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        Auth::logoutCurrentDevice();

        Session::invalidate();
        Session::regenerateToken();

        if ($userId !== null) {
            event(new UserLoggedOut(
                userId: $userId,
                sessionId: $sessionId,
            ));
        }
    }
}
