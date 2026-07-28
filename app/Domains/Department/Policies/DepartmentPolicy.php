<?php

declare(strict_types=1);

namespace App\Domains\Department\Policies;

use App\Domains\Authentication\Models\User;

final class DepartmentPolicy
{
    public function view(User $user): bool
    {
        return $user->can('departments.view');
    }

    public function create(User $user): bool
    {
        return $user->can('departments.create');
    }

    public function update(User $user): bool
    {
        return $user->can('departments.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('departments.delete');
    }

    public function togglePublic(User $user): bool
    {
        return $user->can('departments.publish');
    }

    public function toggleFeatured(User $user): bool
    {
        return $user->can('departments.feature');
    }

    public function reorder(User $user): bool
    {
        return $user->can('departments.reorder');
    }
}
