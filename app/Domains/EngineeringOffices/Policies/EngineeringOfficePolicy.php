<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Policies;

use App\Domains\Authentication\Models\User;

final class EngineeringOfficePolicy
{
    public function view(User $user): bool
    {
        return $user->can('engineering_offices.view');
    }

    public function create(User $user): bool
    {
        return $user->can('engineering_offices.create');
    }

    public function update(User $user): bool
    {
        return $user->can('engineering_offices.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('engineering_offices.delete');
    }

    public function approve(User $user): bool
    {
        return $user->can('engineering_offices.approve');
    }

    public function suspend(User $user): bool
    {
        return $user->can('engineering_offices.suspend');
    }

    public function togglePublic(User $user): bool
    {
        return $user->can('engineering_offices.publish');
    }

    public function reorder(User $user): bool
    {
        return $user->can('engineering_offices.reorder');
    }
}
