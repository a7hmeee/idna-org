<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Domains\Department\Actions\DeleteDepartmentAction;
use App\Domains\Department\Actions\ToggleDepartmentFeaturedAction;
use App\Domains\Department\Actions\ToggleDepartmentPublicAction;
use App\Domains\Department\Enums\DepartmentStatus;
use App\Domains\Department\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class DepartmentShow extends Component
{
    public Department $department;
    public bool $showDeleteModal = false;

    public function mount(Department $department): void
    {
        $this->authorize('view', Department::class);

        $this->department = $department;
    }

    public function togglePublic(ToggleDepartmentPublicAction $action): void
    {
        $this->authorize('togglePublic', Department::class);

        $action->execute($this->department->id);

        $this->department = $this->department->fresh();

        session()->flash('success', 'تم تغيير حالة الظهور للعامة بنجاح.');
    }

    public function toggleFeatured(ToggleDepartmentFeaturedAction $action): void
    {
        $this->authorize('toggleFeatured', Department::class);

        $action->execute($this->department->id);

        $this->department = $this->department->fresh();

        session()->flash('success', 'تم تغيير حالة المميز بنجاح.');
    }

    public function confirmDelete(): void
    {
        $this->authorize('delete', Department::class);

        $this->showDeleteModal = true;
    }

    public function delete(DeleteDepartmentAction $action): void
    {
        $this->authorize('delete', Department::class);

        $action->execute($this->department->id);

        session()->flash('success', 'تم حذف الدائرة بنجاح.');

        $this->redirect(route('dashboard.departments'), navigate: true);
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.department.department-show', [
            'statusLabel' => DepartmentStatus::tryFrom($this->department->status)?->label() ?? $this->department->status,
            'canUpdate' => auth()->user()->can('update', Department::class),
            'canDelete' => auth()->user()->can('delete', Department::class),
            'canTogglePublic' => auth()->user()->can('togglePublic', Department::class),
            'canToggleFeatured' => auth()->user()->can('toggleFeatured', Department::class),
        ]);
    }
}
