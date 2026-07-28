<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Policies;

use App\Domains\Authentication\Models\User;

final class OpenDataPolicy
{
    public function view(User $user): bool
    {
        return $user->can('open_data.view');
    }

    public function create(User $user): bool
    {
        return $user->can('open_data.create');
    }

    public function update(User $user): bool
    {
        return $user->can('open_data.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('open_data.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('open_data.publish');
    }
}
