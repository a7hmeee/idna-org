<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\ChatbotAnalytics\Services\ConversationAnalyticsService;
use App\Domains\ChatbotAnalytics\Services\IntentAnalyticsService;
use App\Domains\ChatbotAnalytics\Services\KnowledgeGapDetectorService;
use App\Domains\ChatbotAnalytics\Services\PerformanceMonitorService;
use Carbon\Carbon;
use Illuminate\Console\Command;

final class ChatbotAnalyticsCommand extends Command
{
    protected $signature = 'chatbot:analytics
        {--days=7 : Number of days to analyse (default 7)}
        {--from= : Start date (Y-m-d, overrides --days)}
        {--to= : End date (Y-m-d)}';

    protected $description = 'Display a quick analytics summary for the chatbot';

    public function handle(
        ConversationAnalyticsService $conversations,
        IntentAnalyticsService $intents,
        KnowledgeGapDetectorService $gaps,
        PerformanceMonitorService $performance,
    ): int {
        $days = (int) $this->option('days');
        $from = $this->option('from')
            ? Carbon::parse($this->option('from'))->startOfDay()
            : Carbon::now()->subDays($days)->startOfDay();
        $to = $this->option('to')
            ? Carbon::parse($this->option('to'))->endOfDay()
            : Carbon::now()->endOfDay();

        $this->info("📊  Chatbot Analytics — {$from->toDateString()} → {$to->toDateString()}");
        $this->newLine();

        // Conversations
        $stats = $conversations->getStats($from, $to);
        $this->line('<fg=cyan>── Conversations ──────────────────────────────</>');
        $this->line("  Total conversations  : {$stats->totalConversations}");
        $this->line("  Active (now)         : {$stats->activeConversations}");
        $this->line("  Total messages       : {$stats->totalMessages}");
        $this->line("  Avg msgs / conv.     : {$stats->avgMessagesPerConversation}");
        $this->line("  Avg response time    : {$stats->avgResponseTimeMs} ms");
        $this->line("  Total feedback       : {$stats->totalFeedback}");
        $this->line("  Positive feedback %  : {$stats->feedbackPositiveRate}%");
        $this->newLine();

        // Intents
        $dist = $intents->getDistributionReport($from, $to, 5);
        $this->line('<fg=cyan>── Intent Distribution ────────────────────────</>');
        $this->line("  Unknown rate         : {$dist->unknownRate}%");
        $this->line("  Avg confidence       : {$dist->avgConfidence}");
        $this->line('  Top intents:');
        foreach ($dist->topIntents as $row) {
            $this->line("    • {$row['intent']}  ({$row['count']})");
        }
        $this->newLine();

        // Gaps
        $gapReport = $gaps->generateReport($from, $to);
        $this->line('<fg=cyan>── Knowledge Gaps ─────────────────────────────</>');
        $this->line("  Unknown questions    : {$gapReport->totalUnknownQuestions}");
        $this->line("  New (unreviewed)     : {$gapReport->newUnknownQuestions}");
        $this->line("  Unknown rate         : {$gapReport->unknownRate}%");
        if (! empty($gapReport->topUnknownQuestions)) {
            $this->line('  Top 5 unknown:');
            foreach (array_slice($gapReport->topUnknownQuestions, 0, 5) as $q) {
                $this->line("    • [{$q['occurrence_count']}×] {$q['question']}");
            }
        }
        $this->newLine();

        // Performance
        $perf = $performance->generateReport($from, $to);
        $this->line('<fg=cyan>── Performance ────────────────────────────────</>');
        $this->line("  Total requests       : {$perf->totalRequests}");
        $this->line("  Avg response time    : {$perf->avgResponseTimeMs} ms");
        $this->line("  P95 response time    : {$perf->p95ResponseTimeMs} ms");
        $this->line("  Slow requests        : {$perf->slowRequests} ({$perf->slowRate}%)");
        $this->newLine();

        return self::SUCCESS;
    }
}
