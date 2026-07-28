<?php

declare(strict_types=1);

namespace App\Domains\News\Policies;

use App\Domains\Authentication\Models\User;

final class NewsPolicy
{
    public function view(User $user): bool
    {
        return $user->can('news.view');
    }

    public function create(User $user): bool
    {
        return $user->can('news.create');
    }

    public function update(User $user): bool
    {
        return $user->can('news.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('news.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('news.publish');
    }

    public function feature(User $user): bool
    {
        return $user->can('news.feature');
    }
}
