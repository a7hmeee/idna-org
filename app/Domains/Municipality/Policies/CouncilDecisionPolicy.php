<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Policies;

use App\Domains\Authentication\Models\User;

final class CouncilDecisionPolicy
{
    public function view(User $user): bool
    {
        return $user->can('council_decisions.view');
    }

    public function create(User $user): bool
    {
        return $user->can('council_decisions.create');
    }

    public function update(User $user): bool
    {
        return $user->can('council_decisions.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('council_decisions.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('council_decisions.publish');
    }

    public function archive(User $user): bool
    {
        return $user->can('council_decisions.archive');
    }

    public function cancel(User $user): bool
    {
        return $user->can('council_decisions.cancel');
    }
}
