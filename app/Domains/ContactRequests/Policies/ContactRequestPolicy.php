<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Policies;

use App\Domains\Authentication\Models\User;
use App\Domains\ContactRequests\Models\ContactRequest;

final class ContactRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('contact_requests.view');
    }

    public function view(User $user, ContactRequest $contactRequest): bool
    {
        return $user->can('contact_requests.view');
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function resolve(User $user, ContactRequest $contactRequest): bool
    {
        return $user->can('contact_requests.resolve');
    }
}
