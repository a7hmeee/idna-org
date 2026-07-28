<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Repositories;

use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final readonly class EloquentComplaintRepository implements ComplaintRepositoryInterface
{
    public function __construct(
        private Complaint $model,
    ) {}

    public function paginateDashboard(?string $search = null, ?string $status = null, ?int $departmentId = null, ?string $priority = null): LengthAwarePaginator
    {
        $query = $this->model->with(['department', 'assignedEmployee', 'creator']);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('tracking_number', 'like', "%{$search}%")
                    ->orWhere('citizen_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('complaint_number', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        return $query->orderBy('submitted_at', 'desc')->paginate(15);
    }

    public function find(int $id): ?Complaint
    {
        return $this->model->with(['department', 'assignedEmployee', 'creator', 'updater'])->find($id);
    }

    public function findByTrackingNumber(string $trackingNumber): ?Complaint
    {
        return $this->model->where('tracking_number', $trackingNumber)->first();
    }

    public function create(array $data): Complaint
    {
        $complaint = $this->model->create($data);

        $this->forgetCache();

        return $complaint->load(['department', 'assignedEmployee']);
    }

    public function update(int $id, array $data): Complaint
    {
        $complaint = $this->findOrFail($id);
        $complaint->update($data);

        $this->forgetCache();

        return $complaint->fresh()->load(['department', 'assignedEmployee', 'creator', 'updater']);
    }

    public function delete(int $id): bool
    {
        $complaint = $this->findOrFail($id);

        return (bool) $complaint->delete();
    }

    public function assign(int $id, int $userId): Complaint
    {
        $complaint = $this->findOrFail($id);
        $complaint->update([
            'assigned_to' => $userId,
            'status' => ComplaintStatus::Assigned,
        ]);

        $this->forgetCache();

        return $complaint->fresh()->load(['assignedEmployee']);
    }

    public function changeStatus(int $id, ComplaintStatus $status): Complaint
    {
        $complaint = $this->findOrFail($id);

        $update = ['status' => $status];

        if ($status === ComplaintStatus::Resolved) {
            $update['resolution_at'] = now();
        }

        $complaint->update($update);

        $this->forgetCache();

        return $complaint->fresh();
    }

    public function respond(int $id, string $publicResponse): Complaint
    {
        $complaint = $this->findOrFail($id);
        $complaint->update([
            'public_response' => $publicResponse,
            'status' => ComplaintStatus::Resolved,
            'resolution_at' => now(),
        ]);

        $this->forgetCache();

        return $complaint->fresh();
    }

    public function incrementViews(int $id): void
    {
        // views_count not stored on complaints table; logged via separate mechanism if needed
    }

    public function getByStatus(ComplaintStatus $status): Collection
    {
        return $this->model->byStatus($status)->with(['department'])->get();
    }

    public function getByDepartment(int $departmentId): Collection
    {
        return $this->model->byDepartment($departmentId)->with(['department'])->get();
    }

    public function getByPriority(ComplaintPriority $priority): Collection
    {
        return $this->model->byPriority($priority)->with(['department'])->get();
    }

    public function getRecent(int $limit = 10): Collection
    {
        return $this->model->with(['department'])
            ->whereNotIn('status', [ComplaintStatus::Closed, ComplaintStatus::Rejected])
            ->orderBy('submitted_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function countByStatus(): Collection
    {
        return $this->model
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');
    }

    private function findOrFail(int $id): Complaint
    {
        $complaint = $this->model->find($id);

        if (!$complaint) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Complaint with ID {$id} not found.");
        }

        return $complaint;
    }

    private function forgetCache(): void
    {
        Cache::forget('complaints_count_by_status');
        Cache::forget('complaints_recent');
    }
}