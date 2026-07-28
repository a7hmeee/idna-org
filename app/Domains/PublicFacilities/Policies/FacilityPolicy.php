<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Policies;

use App\Domains\Authentication\Models\User;

final class FacilityPolicy
{
    public function view(User $user): bool
    {
        return $user->can('facilities.view');
    }

    public function create(User $user): bool
    {
        return $user->can('facilities.create');
    }

    public function update(User $user): bool
    {
        return $user->can('facilities.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('facilities.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('facilities.publish');
    }
}
