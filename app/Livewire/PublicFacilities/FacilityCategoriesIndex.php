<?php

declare(strict_types=1);

namespace App\Livewire\PublicFacilities;

use App\Domains\PublicFacilities\Actions\DeleteFacilityCategoryAction;
use App\Domains\PublicFacilities\Contracts\FacilityCategoryRepositoryInterface;
use App\Domains\PublicFacilities\Models\FacilityCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class FacilityCategoriesIndex extends Component
{
    use WithPagination;

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', FacilityCategory::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteFacilityCategoryAction $action): void
    {
        $this->authorize('delete', FacilityCategory::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف التصنيف بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $categories = app(FacilityCategoryRepositoryInterface::class)->paginateDashboard();

        return view('livewire.public-facilities.facility-categories-index', [
            'categories' => $categories,
        ]);
    }
}
