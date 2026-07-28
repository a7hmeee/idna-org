<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Policies;

use App\Domains\Authentication\Models\User;

final class HomepagePolicy
{
    public function view(User $user): bool
    {
        return $user->can('homepage.view');
    }

    public function update(User $user): bool
    {
        return $user->can('homepage.update');
    }

    public function viewSlides(User $user): bool
    {
        return $user->can('homepage.slides.view');
    }

    public function createSlide(User $user): bool
    {
        return $user->can('homepage.slides.create');
    }

    public function updateSlide(User $user): bool
    {
        return $user->can('homepage.slides.update');
    }

    public function deleteSlide(User $user): bool
    {
        return $user->can('homepage.slides.delete');
    }

    public function reorderSlides(User $user): bool
    {
        return $user->can('homepage.slides.reorder');
    }

    public function updateSection(User $user): bool
    {
        return $user->can('homepage.sections.update');
    }

    public function viewQuickLinks(User $user): bool
    {
        return $user->can('homepage.quick_links.view');
    }

    public function createQuickLink(User $user): bool
    {
        return $user->can('homepage.quick_links.create');
    }

    public function updateQuickLink(User $user): bool
    {
        return $user->can('homepage.quick_links.update');
    }

    public function deleteQuickLink(User $user): bool
    {
        return $user->can('homepage.quick_links.delete');
    }

    public function reorderQuickLinks(User $user): bool
    {
        return $user->can('homepage.quick_links.reorder');
    }

    public function viewStatistics(User $user): bool
    {
        return $user->can('homepage.statistics.view');
    }

    public function createStatistic(User $user): bool
    {
        return $user->can('homepage.statistics.create');
    }

    public function updateStatistic(User $user): bool
    {
        return $user->can('homepage.statistics.update');
    }

    public function deleteStatistic(User $user): bool
    {
        return $user->can('homepage.statistics.delete');
    }

    public function reorderStatistics(User $user): bool
    {
        return $user->can('homepage.statistics.reorder');
    }
}
