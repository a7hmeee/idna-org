<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Domains\Department\Actions\DeleteDepartmentAction;
use App\Domains\Department\Actions\ToggleDepartmentFeaturedAction;
use App\Domains\Department\Actions\ToggleDepartmentPublicAction;
use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\Department\Enums\DepartmentStatus;
use App\Domains\Department\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class DepartmentsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('view', Department::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', Department::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteDepartmentAction $action): void
    {
        $this->authorize('delete', Department::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الدائرة بنجاح.');
    }

    public function togglePublic(int $id, ToggleDepartmentPublicAction $action): void
    {
        $this->authorize('togglePublic', Department::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة الظهور للعامة بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleDepartmentFeaturedAction $action): void
    {
        $this->authorize('toggleFeatured', Department::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة المميز بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $departments = app(DepartmentRepositoryInterface::class)
            ->paginateDashboard(
                search: $this->search ?: null,
                status: $this->status ?: null,
                perPage: 15
            );

        return view('livewire.department.departments-index', [
            'departments' => $departments,
            'statusOptions' => DepartmentStatus::options(),
            'canCreate' => auth()->user()->can('create', Department::class),
            'canUpdate' => auth()->user()->can('update', Department::class),
            'canDelete' => auth()->user()->can('delete', Department::class),
            'canTogglePublic' => auth()->user()->can('togglePublic', Department::class),
            'canToggleFeatured' => auth()->user()->can('toggleFeatured', Department::class),
        ]);
    }
}
