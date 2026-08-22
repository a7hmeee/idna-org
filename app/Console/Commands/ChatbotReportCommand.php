<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\ChatbotAnalytics\Services\ConversationAnalyticsService;
use App\Domains\ChatbotAnalytics\Services\IntentAnalyticsService;
use App\Domains\ChatbotAnalytics\Services\KnowledgeGapDetectorService;
use App\Domains\ChatbotAnalytics\Services\PerformanceMonitorService;
use Carbon\Carbon;
use Illuminate\Console\Command;

final class ChatbotReportCommand extends Command
{
    protected $signature = 'chatbot:report
        {--period=30 : Period in days (default 30)}
        {--format=text : Output format: text or json}';

    protected $description = 'Generate a detailed chatbot analytics report';

    public function handle(
        ConversationAnalyticsService $conversations,
        IntentAnalyticsService $intents,
        KnowledgeGapDetectorService $gaps,
        PerformanceMonitorService $performance,
    ): int {
        $days = (int) $this->option('period');
        $format = $this->option('format');

        $from = Carbon::now()->subDays($days)->startOfDay();
        $to = Carbon::now()->endOfDay();

        $report = [
            'period' => ['from' => $from->toDateString(), 'to' => $to->toDateString(), 'days' => $days],
            'conversations' => $conversations->getStats($from, $to)->toArray(),
            'intents' => $intents->getDistributionReport($from, $to)->toArray(),
            'knowledge_gaps' => $gaps->generateReport($from, $to)->toArray(),
            'performance' => $performance->generateReport($from, $to)->toArray(),
        ];

        if ($format === 'json') {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        }

        $this->info("=== Chatbot Report ({$from->toDateString()} → {$to->toDateString()}) ===");
        $this->newLine();

        $this->renderSection('CONVERSATIONS', $report['conversations']);
        $this->renderSection('INTENTS', $report['intents']);
        $this->renderSection('KNOWLEDGE GAPS', $report['knowledge_gaps']);
        $this->renderSection('PERFORMANCE', $report['performance']);

        return self::SUCCESS;
    }

    private function renderSection(string $title, array $data): void
    {
        $this->line("<fg=yellow>▶ {$title}</>");
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->line("  {$key}: [...]");
            } else {
                $this->line("  {$key}: {$value}");
            }
        }
        $this->newLine();
    }
}
