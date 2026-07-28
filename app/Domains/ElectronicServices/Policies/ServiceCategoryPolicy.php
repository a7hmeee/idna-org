<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Policies;

use App\Domains\Authentication\Models\User;

final class ServiceCategoryPolicy
{
    public function view(User $user): bool
    {
        return $user->can('service_categories.view');
    }

    public function create(User $user): bool
    {
        return $user->can('service_categories.create');
    }

    public function update(User $user): bool
    {
        return $user->can('service_categories.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('service_categories.delete');
    }

    public function togglePublic(User $user): bool
    {
        return $user->can('service_categories.publish');
    }

    public function reorder(User $user): bool
    {
        return $user->can('service_categories.reorder');
    }
}
