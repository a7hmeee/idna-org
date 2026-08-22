<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Chatbot;

use App\Domains\ChatbotAnalytics\Services\ConversationAnalyticsService;
use App\Domains\ChatbotAnalytics\Services\IntentAnalyticsService;
use App\Domains\ChatbotAnalytics\Services\KnowledgeGapDetectorService;
use App\Domains\ChatbotAnalytics\Services\PerformanceMonitorService;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ChatbotDashboard extends Component
{
    public string $period = '7';  // days

    public array $conversationStats = [];

    public array $intentDistribution = [];

    public array $knowledgeGaps = [];

    public array $performanceStats = [];

    public function mount(): void
    {
        $this->loadStats();
    }

    public function updatedPeriod(): void
    {
        $this->loadStats();
    }

    #[On('refresh-stats')]
    public function loadStats(): void
    {
        $from = Carbon::now()->subDays((int) $this->period)->startOfDay();
        $to = Carbon::now()->endOfDay();

        $conversationService = app(ConversationAnalyticsService::class);
        $intentService = app(IntentAnalyticsService::class);
        $gapService = app(KnowledgeGapDetectorService::class);
        $performanceService = app(PerformanceMonitorService::class);

        $this->conversationStats = $conversationService->getStats($from, $to)->toArray();
        $this->intentDistribution = $intentService->getDistributionReport($from, $to, 10)->toArray();
        $this->knowledgeGaps = $gapService->generateReport($from, $to)->toArray();
        $this->performanceStats = $performanceService->generateReport($from, $to)->toArray();
    }

    public function render(): View
    {
        return view('livewire.admin.chatbot.chatbot-dashboard');
    }
}
