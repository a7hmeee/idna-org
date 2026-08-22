<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Services;

use App\Domains\ChatbotAnalytics\Contracts\UnknownQuestionRepositoryInterface;
use App\Domains\ChatbotAnalytics\DTOs\KnowledgeGapReportData;
use App\Domains\ChatbotAnalytics\Models\ChatbotUnknownQuestion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Detects and reports knowledge gaps in the chatbot training data.
 */
final readonly class KnowledgeGapDetectorService
{
    public function __construct(
        private UnknownQuestionRepositoryInterface $unknownRepository,
    ) {}

    public function generateReport(Carbon $from, Carbon $to): KnowledgeGapReportData
    {
        $total = $this->unknownRepository->getTotalCount();
        $new = $this->unknownRepository->getTotalCount('new');
        $reviewed = $this->unknownRepository->getTotalCount('reviewed');
        $resolved = $this->unknownRepository->getTotalCount('resolved');

        $topUnknown = $this->unknownRepository->getTopUnknown(20, $from, $to);

        // Unknown rate vs total intent records in same period
        $totalIntents = DB::table('chatbot_intent_analytics')
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $unknownIntents = DB::table('chatbot_intent_analytics')
            ->whereBetween('created_at', [$from, $to])
            ->where('is_unknown', true)
            ->count();

        $unknownRate = $totalIntents > 0
            ? round($unknownIntents / $totalIntents * 100, 2)
            : 0.0;

        // Suggested domains from unknown questions
        $suggestedDomains = DB::table('chatbot_unknown_questions')
            ->whereNotNull('suggested_domain')
            ->groupBy('suggested_domain')
            ->selectRaw('suggested_domain, count(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->pluck('count', 'suggested_domain')
            ->toArray();

        return new KnowledgeGapReportData(
            totalUnknownQuestions: $total,
            newUnknownQuestions: $new,
            reviewedQuestions: $reviewed,
            resolvedQuestions: $resolved,
            topUnknownQuestions: $topUnknown,
            unknownRate: $unknownRate,
            suggestedDomains: $suggestedDomains,
        );
    }

    /**
     * Returns unknown questions that have not been resolved and exceed threshold occurrences.
     */
    public function getCriticalGaps(int $minOccurrences = 3): array
    {
        return ChatbotUnknownQuestion::where('admin_status', 'new')
            ->where('occurrence_count', '>=', $minOccurrences)
            ->orderByDesc('occurrence_count')
            ->get(['id', 'question', 'occurrence_count', 'last_seen_at', 'suggested_domain'])
            ->toArray();
    }
}
