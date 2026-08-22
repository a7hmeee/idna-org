<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Chatbot;

use App\Domains\ChatbotAnalytics\Services\PerformanceMonitorService;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class PerformanceMonitor extends Component
{
    public string $period = '7';

    public array $report = [];

    public function mount(): void
    {
        $this->loadReport();
    }

    public function updatedPeriod(): void
    {
        $this->loadReport();
    }

    public function loadReport(): void
    {
        $from = Carbon::now()->subDays((int) $this->period)->startOfDay();
        $to = Carbon::now()->endOfDay();

        $this->report = app(PerformanceMonitorService::class)
            ->generateReport($from, $to)
            ->toArray();
    }

    public function render(): View
    {
        return view('livewire.admin.chatbot.performance-monitor');
    }
}
