<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\Department\Models\Department;
use App\Domains\ElectronicServices\Actions\DeleteElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\ToggleElectronicServiceFeaturedAction;
use App\Domains\ElectronicServices\Actions\ToggleElectronicServicePublicAction;
use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\Enums\ElectronicServiceStatus;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class ElectronicServicesIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $categoryId = '';

    public string $departmentId = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('view', ElectronicService::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingDepartmentId(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', ElectronicService::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteElectronicServiceAction $action): void
    {
        $this->authorize('delete', ElectronicService::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الخدمة بنجاح.');
    }

    public function togglePublic(int $id, ToggleElectronicServicePublicAction $action): void
    {
        $this->authorize('togglePublic', ElectronicService::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة الظهور بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleElectronicServiceFeaturedAction $action): void
    {
        $this->authorize('toggleFeatured', ElectronicService::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة التمييز بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $services = app(ElectronicServiceRepositoryInterface::class)
            ->paginateDashboard(
                search: $this->search ?: null,
                status: $this->status ?: null,
                categoryId: $this->categoryId ? (int) $this->categoryId : null,
                departmentId: $this->departmentId ? (int) $this->departmentId : null,
                perPage: 15
            );

        return view('livewire.electronic-services.electronic-services-index', [
            'services' => $services,
            'statusOptions' => ElectronicServiceStatus::options(),
            'categories' => ServiceCategory::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'canCreate' => auth()->user()->can('create', ElectronicService::class),
            'canUpdate' => auth()->user()->can('update', ElectronicService::class),
            'canDelete' => auth()->user()->can('delete', ElectronicService::class),
            'canTogglePublic' => auth()->user()->can('togglePublic', ElectronicService::class),
            'canToggleFeatured' => auth()->user()->can('toggleFeatured', ElectronicService::class),
        ]);
    }
}
