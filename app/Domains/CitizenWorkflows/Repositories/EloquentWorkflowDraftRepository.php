<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Repositories;

use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\Models\WorkflowDraft;
use Illuminate\Support\Collection;

final readonly class EloquentWorkflowDraftRepository implements WorkflowDraftRepositoryInterface
{
    public function __construct(
        private WorkflowDraft $model,
    ) {}

    private const ACTIVE_STATUSES = ['collecting_data', 'waiting_confirmation', 'waiting_attachment', 'confirming'];

    public function findActiveBySession(string $sessionId): ?WorkflowDraft
    {
        return $this->model
            ->where('session_id', $sessionId)
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->latest()
            ->first();
    }

    public function findResumableBySession(string $sessionId): ?WorkflowDraft
    {
        return $this->model
            ->where('session_id', $sessionId)
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->where(function ($q): void {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();
    }

    public function findActiveByUser(int $userId): ?WorkflowDraft
    {
        return $this->model
            ->where('citizen_user_id', $userId)
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->latest()
            ->first();
    }

    public function create(array $data): WorkflowDraft
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): WorkflowDraft
    {
        $draft = $this->model->findOrFail($id);
        $draft->update($data);

        return $draft->fresh();
    }

    public function complete(int $id): void
    {
        $this->model->where('id', $id)->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function cancel(int $id): void
    {
        $this->model->where('id', $id)->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function expire(int $id): void
    {
        $this->model->where('id', $id)->update(['status' => 'expired']);
    }

    public function findByTracking(string $trackingNumber): ?WorkflowDraft
    {
        return $this->model->where('tracking_number', $trackingNumber)->first();
    }

    public function allActive(): Collection
    {
        return $this->model
            ->whereIn('status', ['collecting_data', 'waiting_confirmation', 'waiting_attachment'])
            ->get();
    }
}
