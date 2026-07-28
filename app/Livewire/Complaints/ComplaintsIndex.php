<?php

declare(strict_types=1);

namespace App\Livewire\Complaints;

use App\Domains\Complaints\Actions\DeleteComplaintAction;
use App\Domains\Complaints\Actions\AssignComplaintAction;
use App\Domains\Complaints\Actions\ChangeStatusAction;
use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\Department\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class ComplaintsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $departmentFilter = '';
    public string $priorityFilter = '';
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public bool $showAssignModal = false;
    public ?int $assigningId = null;
    public ?int $assignedUserId = null;
    public bool $showStatusModal = false;
    public ?int $statusChangeId = null;
    public string $newStatus = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', Complaint::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteComplaintAction $action): void
    {
        $this->authorize('delete', Complaint::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الشكوى بنجاح.');
    }

    public function confirmAssign(int $id): void
    {
        $this->authorize('assign', Complaint::class);

        $this->assigningId = $id;
        $this->assignedUserId = null;
        $this->showAssignModal = true;
    }

    public function assign(AssignComplaintAction $action): void
    {
        $this->authorize('assign', Complaint::class);

        $this->validate(['assignedUserId' => ['required', 'integer', 'exists:users,id']]);

        $action->execute($this->assigningId, $this->assignedUserId);

        $this->showAssignModal = false;
        $this->assigningId = null;
        $this->assignedUserId = null;
        session()->flash('success', 'تم تعيين الشكوى بنجاح.');
    }

    public function confirmStatusChange(int $id): void
    {
        $this->authorize('changeStatus', Complaint::class);

        $this->statusChangeId = $id;
        $this->newStatus = '';
        $this->showStatusModal = true;
    }

    public function changeStatus(ChangeStatusAction $action): void
    {
        $this->authorize('changeStatus', Complaint::class);

        $this->validate(['newStatus' => ['required', 'string', 'in:' . implode(',', array_map(fn($s) => $s->value, ComplaintStatus::cases()))]]);

        $action->execute($this->statusChangeId, ComplaintStatus::from($this->newStatus));

        $this->showStatusModal = false;
        $this->statusChangeId = null;
        $this->newStatus = '';
        session()->flash('success', 'تم تغيير حالة الشكوى بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function closeAssignModal(): void
    {
        $this->showAssignModal = false;
        $this->assigningId = null;
        $this->assignedUserId = null;
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->statusChangeId = null;
        $this->newStatus = '';
    }

    public function render()
    {
        $complaints = app(ComplaintRepositoryInterface::class)->paginateDashboard(
            search: $this->search ?: null,
            status: $this->statusFilter ?: null,
            departmentId: $this->departmentFilter ? (int) $this->departmentFilter : null,
            priority: $this->priorityFilter ?: null,
        );

        $departments = Department::where('is_public', true)->orderBy('name')->get();
        $statuses = ComplaintStatus::cases();
        $priorities = ComplaintPriority::cases();
        $employees = \App\Domains\Authentication\Models\User::where('status', 'active')->orderBy('name')->get();

        return view('livewire.complaints.complaints-index', compact(
            'complaints', 'departments', 'statuses', 'priorities', 'employees'
        ));
    }
}