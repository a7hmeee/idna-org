<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Contracts;

use App\Domains\Municipality\Models\CouncilDecision;
use Illuminate\Pagination\LengthAwarePaginator;

interface CouncilDecisionRepositoryInterface
{
    public function paginateForDashboard(?string $search = null, ?string $status = null, ?string $type = null, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?CouncilDecision;

    public function create(array $data): CouncilDecision;

    public function update(int $id, array $data): CouncilDecision;

    public function delete(int $id): bool;

    public function publish(int $id): CouncilDecision;

    public function archive(int $id): CouncilDecision;

    public function cancel(int $id): CouncilDecision;

    // Public queries
    public function paginatePublicDecisions(?string $search = null, ?string $type = null, ?int $year = null, string $sort = 'latest', int $perPage = 12): LengthAwarePaginator;

    public function findPublicById(int $id): ?CouncilDecision;

    public function getRelatedPublishedDecisions(int $decisionId, string $type, int $limit = 3): array;

    public function getPreviousDecision(int $decisionId, \DateTimeInterface|string $decisionDate): ?CouncilDecision;

    public function getNextDecision(int $decisionId, \DateTimeInterface|string $decisionDate): ?CouncilDecision;

    public function getPublicYears(): array;

    public function getPublicStatistics(): array;

    public function getHomepagePublishedDecisions(int $limit = 5): array;

    public function getLatestPublished(int $limit = 5): array;
}
