<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Policies;

use App\Domains\Authentication\Models\User;

final class ComplaintPolicy
{
    public function view(User $user): bool
    {
        return $user->can('view complaints');
    }

    public function create(User $user): bool
    {
        return $user->can('create complaints');
    }

    public function update(User $user): bool
    {
        return $user->can('edit complaints');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete complaints');
    }

    public function assign(User $user): bool
    {
        return $user->can('edit complaints');
    }

    public function changeStatus(User $user): bool
    {
        return $user->can('edit complaints');
    }

    public function respond(User $user): bool
    {
        return $user->can('reply complaints');
    }

    public function export(User $user): bool
    {
        return $user->can('view complaints');
    }
}