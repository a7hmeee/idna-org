<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\ElectronicServices\Actions\DeleteServiceCategoryAction;
use App\Domains\ElectronicServices\Actions\ToggleServiceCategoryPublicAction;
use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;
use App\Domains\ElectronicServices\Enums\ServiceCategoryStatus;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class ServiceCategoriesIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('view', ServiceCategory::class);
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
        $this->authorize('delete', ServiceCategory::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteServiceCategoryAction $action): void
    {
        $this->authorize('delete', ServiceCategory::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف التصنيف بنجاح.');
    }

    public function togglePublic(int $id, ToggleServiceCategoryPublicAction $action): void
    {
        $this->authorize('togglePublic', ServiceCategory::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة الظهور بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $categories = app(ServiceCategoryRepositoryInterface::class)
            ->paginateDashboard(
                search: $this->search ?: null,
                status: $this->status ?: null,
                perPage: 15
            );

        return view('livewire.electronic-services.service-categories-index', [
            'categories' => $categories,
            'statusOptions' => ServiceCategoryStatus::options(),
            'canCreate' => auth()->user()->can('create', ServiceCategory::class),
            'canUpdate' => auth()->user()->can('update', ServiceCategory::class),
            'canDelete' => auth()->user()->can('delete', ServiceCategory::class),
            'canTogglePublic' => auth()->user()->can('togglePublic', ServiceCategory::class),
        ]);
    }
}
