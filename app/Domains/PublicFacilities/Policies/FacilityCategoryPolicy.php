<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Policies;

use App\Domains\Authentication\Models\User;

final class FacilityCategoryPolicy
{
    public function view(User $user): bool
    {
        return $user->can('facility_categories.view');
    }

    public function create(User $user): bool
    {
        return $user->can('facility_categories.create');
    }

    public function update(User $user): bool
    {
        return $user->can('facility_categories.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('facility_categories.delete');
    }
}
