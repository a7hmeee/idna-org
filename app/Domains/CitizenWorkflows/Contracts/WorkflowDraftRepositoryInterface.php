<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Contracts;

use App\Domains\CitizenWorkflows\Models\WorkflowDraft;
use Illuminate\Support\Collection;

interface WorkflowDraftRepositoryInterface
{
    public function findActiveBySession(string $sessionId): ?WorkflowDraft;

    public function findResumableBySession(string $sessionId): ?WorkflowDraft;

    public function findActiveByUser(int $userId): ?WorkflowDraft;

    public function create(array $data): WorkflowDraft;

    public function update(int $id, array $data): WorkflowDraft;

    public function complete(int $id): void;

    public function cancel(int $id): void;

    public function expire(int $id): void;

    public function findByTracking(string $trackingNumber): ?WorkflowDraft;

    public function allActive(): Collection;
}
