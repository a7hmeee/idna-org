<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Contracts;

use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ComplaintRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, ?int $departmentId = null, ?string $priority = null): LengthAwarePaginator;

    public function find(int $id): ?Complaint;

    public function findByTrackingNumber(string $trackingNumber): ?Complaint;

    public function create(array $data): Complaint;

    public function update(int $id, array $data): Complaint;

    public function delete(int $id): bool;

    public function assign(int $id, int $userId): Complaint;

    public function changeStatus(int $id, ComplaintStatus $status): Complaint;

    public function respond(int $id, string $publicResponse): Complaint;

    public function incrementViews(int $id): void;

    public function getByStatus(ComplaintStatus $status): Collection;

    public function getByDepartment(int $departmentId): Collection;

    public function getByPriority(ComplaintPriority $priority): Collection;

    public function getRecent(int $limit = 10): Collection;

    public function countByStatus(): Collection;
}
