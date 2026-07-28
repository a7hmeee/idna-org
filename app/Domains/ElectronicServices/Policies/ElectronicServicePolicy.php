<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Policies;

use App\Domains\Authentication\Models\User;

final class ElectronicServicePolicy
{
    public function view(User $user): bool
    {
        return $user->can('electronic_services.view');
    }

    public function create(User $user): bool
    {
        return $user->can('electronic_services.create');
    }

    public function update(User $user): bool
    {
        return $user->can('electronic_services.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('electronic_services.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('electronic_services.publish');
    }

    public function toggleFeatured(User $user): bool
    {
        return $user->can('electronic_services.feature');
    }

    public function viewAnalytics(User $user): bool
    {
        return $user->can('electronic_services.analytics');
    }
}
