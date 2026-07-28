<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Domains\Dashboard\Contracts\DashboardRepositoryInterface;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ExecutiveDashboard extends Component
{
    public array $dashboardData = [];

    public function mount(): void
    {
        $this->dashboardData = app(DashboardRepositoryInterface::class)->getExecutiveDashboard();
    }

    public function render()
    {
        return view('livewire.dashboard.executive-dashboard', [
            'data' => $this->dashboardData,
        ]);
    }
}
