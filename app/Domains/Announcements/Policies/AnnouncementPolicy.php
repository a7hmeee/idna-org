<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Policies;

use App\Domains\Authentication\Models\User;

final class AnnouncementPolicy
{
    public function view(User $user): bool
    {
        return $user->can('announcements.view');
    }

    public function create(User $user): bool
    {
        return $user->can('announcements.create');
    }

    public function update(User $user): bool
    {
        return $user->can('announcements.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('announcements.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('announcements.publish');
    }

    public function reorder(User $user): bool
    {
        return $user->can('announcements.reorder');
    }
}
