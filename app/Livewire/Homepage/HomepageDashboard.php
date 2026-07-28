<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class HomepageDashboard extends Component
{
    public function mount(): void
    {
        $this->authorize('view', HomepageSetting::class);
    }

    public function render()
    {
        $repo = app(HomepageRepositoryInterface::class);

        $settings = $repo->getSettings();
        $slidesCount = $repo->getActiveSlides()->count();
        $totalSlides = $repo->paginateSlides()->total();
        $quickLinksCount = $repo->getQuickLinks()->count();
        $totalQuickLinks = $repo->paginateQuickLinks()->total();
        $statisticsCount = $repo->getStatistics()->count();
        $totalStatistics = $repo->paginateStatistics()->total();
        $enabledSectionsCount = $repo->getEnabledSections()->count();
        $totalSections = $repo->getSections()->count();

        return view('livewire.homepage.dashboard', compact(
            'settings', 'slidesCount', 'totalSlides',
            'quickLinksCount', 'totalQuickLinks',
            'statisticsCount', 'totalStatistics',
            'enabledSectionsCount', 'totalSections',
        ));
    }
}
