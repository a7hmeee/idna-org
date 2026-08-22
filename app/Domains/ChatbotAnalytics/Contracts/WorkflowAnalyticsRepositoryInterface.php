<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Contracts;

use App\Domains\ChatbotAnalytics\Models\ChatbotWorkflowAnalytics;
use Carbon\Carbon;

interface WorkflowAnalyticsRepositoryInterface
{
    public function create(array $data): ChatbotWorkflowAnalytics;

    public function updateCompletion(int $id, array $data): void;

    public function getCompletionRate(string $workflowType, Carbon $from, Carbon $to): float;

    public function getCancellationRate(string $workflowType, Carbon $from, Carbon $to): float;

    public function getAverageDuration(string $workflowType, Carbon $from, Carbon $to): float;

    public function getStats(Carbon $from, Carbon $to): array;

    public function findByDraftId(int $draftId): ?ChatbotWorkflowAnalytics;
}
