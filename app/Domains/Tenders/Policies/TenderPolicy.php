<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Policies;

use App\Domains\Authentication\Models\User;

final class TenderPolicy
{
    public function view(User $user): bool
    {
        return $user->can('tenders.view');
    }

    public function create(User $user): bool
    {
        return $user->can('tenders.create');
    }

    public function update(User $user): bool
    {
        return $user->can('tenders.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('tenders.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('tenders.publish');
    }

    public function archive(User $user): bool
    {
        return $user->can('tenders.archive');
    }
}
