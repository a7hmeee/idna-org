<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Policies;

use App\Domains\ContactRequests\Models\ContactRequest;
use App\Domains\UserManagement\Models\User;

final class ContactRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, ContactRequest $contactRequest): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function resolve(User $user, ContactRequest $contactRequest): bool
    {
        return $user->hasRole('admin');
    }
}
