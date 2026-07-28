<?php

declare(strict_types=1);

namespace App\Domains\Projects\Policies;

use App\Domains\Authentication\Models\User;

final class ProjectPolicy
{
    public function view(User $user): bool
    {
        return $user->can('projects.view');
    }

    public function create(User $user): bool
    {
        return $user->can('projects.create');
    }

    public function update(User $user): bool
    {
        return $user->can('projects.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('projects.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('projects.publish');
    }

    public function feature(User $user): bool
    {
        return $user->can('projects.feature');
    }
}
