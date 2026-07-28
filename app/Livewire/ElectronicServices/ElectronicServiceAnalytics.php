<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\ElectronicServices\Contracts\ServiceAnalyticsRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ElectronicServiceAnalytics extends Component
{
    public function mount(): void
    {
        $this->authorize('viewAnalytics', ElectronicService::class);
    }

    public function render()
    {
        $analytics = app(ServiceAnalyticsRepositoryInterface::class);

        return view('livewire.electronic-services.electronic-service-analytics', [
            'stats' => $analytics->dashboardStats(),
            'topViewed' => $analytics->topViewedServices(10),
            'topClicked' => $analytics->topClickedServices(10),
        ]);
    }
}
