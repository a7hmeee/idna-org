<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Policies;

use App\Domains\Authentication\Models\User;

final class AuthenticationPolicy
{
    public function viewLogin(User $user): bool
    {
        return $user->hasPermissionTo('access panel');
    }

    public function viewSessions(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin']);
    }

    public function terminateSession(User $user, string $sessionId): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function viewActivityLogs(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin']);
    }
}
