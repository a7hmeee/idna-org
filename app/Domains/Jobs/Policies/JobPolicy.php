<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Policies;

use App\Domains\Authentication\Models\User;

final class JobPolicy
{
    public function view(User $user): bool
    {
        return $user->can('jobs.view');
    }

    public function create(User $user): bool
    {
        return $user->can('jobs.create');
    }

    public function update(User $user): bool
    {
        return $user->can('jobs.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('jobs.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('jobs.publish');
    }

    public function archive(User $user): bool
    {
        return $user->can('jobs.archive');
    }
}
