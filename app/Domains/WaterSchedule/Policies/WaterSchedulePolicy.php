<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Policies;

use App\Domains\Authentication\Models\User;

final class WaterSchedulePolicy
{
    public function view(User $user): bool
    {
        return $user->can('water.view');
    }

    public function create(User $user): bool
    {
        return $user->can('water.create');
    }

    public function update(User $user): bool
    {
        return $user->can('water.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('water.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('water.publish');
    }
}
