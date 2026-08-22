<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Repositories;

use App\Domains\ChatbotAnalytics\Contracts\WorkflowAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Models\ChatbotWorkflowAnalytics;
use Carbon\Carbon;

final readonly class EloquentWorkflowAnalyticsRepository implements WorkflowAnalyticsRepositoryInterface
{
    public function __construct(
        private ChatbotWorkflowAnalytics $model,
    ) {}

    public function create(array $data): ChatbotWorkflowAnalytics
    {
        return $this->model->create($data);
    }

    public function updateCompletion(int $id, array $data): void
    {
        $this->model->where('id', $id)->update($data);
    }

    public function getCompletionRate(string $workflowType, Carbon $from, Carbon $to): float
    {
        $total = $this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('workflow_type', $workflowType)
            ->count();

        if ($total === 0) {
            return 0.0;
        }

        $completed = $this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('workflow_type', $workflowType)
            ->where('was_completed', true)
            ->count();

        return round($completed / $total * 100, 2);
    }

    public function getCancellationRate(string $workflowType, Carbon $from, Carbon $to): float
    {
        $total = $this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('workflow_type', $workflowType)
            ->count();

        if ($total === 0) {
            return 0.0;
        }

        $cancelled = $this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('workflow_type', $workflowType)
            ->where('was_cancelled', true)
            ->count();

        return round($cancelled / $total * 100, 2);
    }

    public function getAverageDuration(string $workflowType, Carbon $from, Carbon $to): float
    {
        return (float) ($this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('workflow_type', $workflowType)
            ->avg('duration_ms') ?? 0.0);
    }

    public function getStats(Carbon $from, Carbon $to): array
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('workflow_type')
            ->selectRaw('
                workflow_type,
                count(*) as total,
                sum(case when was_completed = 1 then 1 else 0 end) as completed,
                sum(case when was_cancelled = 1 then 1 else 0 end) as cancelled,
                avg(duration_ms) as avg_duration_ms
            ')
            ->get()
            ->toArray();
    }

    public function findByDraftId(int $draftId): ?ChatbotWorkflowAnalytics
    {
        return $this->model->where('workflow_draft_id', $draftId)->latest()->first();
    }
}
