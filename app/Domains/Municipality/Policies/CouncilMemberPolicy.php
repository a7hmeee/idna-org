<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Policies;

use App\Domains\Authentication\Models\User;

final class CouncilMemberPolicy
{
    public function view(User $user): bool
    {
        return $user->can('council_members.view');
    }

    public function create(User $user): bool
    {
        return $user->can('council_members.create');
    }

    public function update(User $user): bool
    {
        return $user->can('council_members.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('council_members.delete');
    }

    public function togglePublic(User $user): bool
    {
        return $user->can('council_members.toggle-public');
    }

    public function toggleFeatured(User $user): bool
    {
        return $user->can('council_members.toggle-featured');
    }

    public function reorder(User $user): bool
    {
        return $user->can('council_members.reorder');
    }
}
