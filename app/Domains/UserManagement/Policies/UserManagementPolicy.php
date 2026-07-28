<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\Policies;

use App\Domains\Authentication\Models\User;

final class UserManagementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view users');
    }

    public function view(User $user): bool
    {
        return $user->can('view users');
    }

    public function create(User $user): bool
    {
        return $user->can('create users');
    }

    public function update(User $user): bool
    {
        return $user->can('edit users');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete users');
    }

    public function manageRoles(User $user): bool
    {
        return $user->can('assign roles');
    }

    public function resetPassword(User $user): bool
    {
        return $user->can('edit users');
    }
}
