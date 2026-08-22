<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Services;

use App\Domains\Chatbot\Models\ChatbotConversation;
use App\Domains\ChatbotAnalytics\DTOs\ConversationStatsData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Aggregates conversation-level statistics for the analytics dashboard.
 */
final readonly class ConversationAnalyticsService
{
    public function getStats(Carbon $from, Carbon $to): ConversationStatsData
    {
        $conversations = ChatbotConversation::whereBetween('created_at', [$from, $to]);

        $total = (clone $conversations)->count();
        $active = ChatbotConversation::where('status', 'active')->count();

        $totalMessages = DB::table('chatbot_messages')
            ->whereIn('conversation_id', (clone $conversations)->pluck('id'))
            ->count();

        $avgMessages = $total > 0
            ? round($totalMessages / $total, 2)
            : 0.0;

        // Avg response time from intent analytics
        $avgResponseTimeMs = (float) (DB::table('chatbot_intent_analytics')
            ->whereBetween('created_at', [$from, $to])
            ->avg('execution_time_ms') ?? 0.0);

        // Feedback stats
        $feedbackData = DB::table('chatbot_feedback')
            ->join('chatbot_messages', 'chatbot_feedback.message_id', '=', 'chatbot_messages.id')
            ->whereIn('chatbot_messages.conversation_id', (clone $conversations)->pluck('id'))
            ->selectRaw('count(*) as total, sum(case when chatbot_feedback.type = "positive" then 1 else 0 end) as positive')
            ->first();

        $totalFeedback = (int) ($feedbackData?->total ?? 0);
        $positiveFeedback = (int) ($feedbackData?->positive ?? 0);
        $feedbackRate = $totalFeedback > 0 ? round($positiveFeedback / $totalFeedback * 100, 2) : 0.0;

        return new ConversationStatsData(
            totalConversations: $total,
            totalMessages: $totalMessages,
            activeConversations: $active,
            avgMessagesPerConversation: $avgMessages,
            avgResponseTimeMs: round($avgResponseTimeMs, 2),
            successfulConversations: 0,  // reserved for future status tracking
            failedConversations: 0,
            escalatedConversations: 0,
            feedbackPositiveRate: $feedbackRate,
            totalFeedback: $totalFeedback,
        );
    }
}
