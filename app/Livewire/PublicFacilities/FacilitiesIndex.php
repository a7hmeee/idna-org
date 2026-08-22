<?php

declare(strict_types=1);

namespace App\Livewire\PublicFacilities;

use App\Domains\PublicFacilities\Actions\ArchiveFacilityAction;
use App\Domains\PublicFacilities\Actions\DeleteFacilityAction;
use App\Domains\PublicFacilities\Actions\PublishFacilityAction;
use App\Domains\PublicFacilities\Actions\ToggleFeaturedFacilityAction;
use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use App\Domains\PublicFacilities\Models\Facility;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class FacilitiesIndex extends Component
{
    use WithPagination;

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', Facility::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteFacilityAction $action): void
    {
        $this->authorize('delete', Facility::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف المرفق بنجاح.');
    }

    public function publish(int $id, PublishFacilityAction $action): void
    {
        $this->authorize('publish', Facility::class);

        $action->execute($id);

        session()->flash('success', 'تم نشر المرفق بنجاح.');
    }

    public function archive(int $id, ArchiveFacilityAction $action): void
    {
        $this->authorize('update', Facility::class);

        $action->execute($id);

        session()->flash('success', 'تم أرشفة المرفق بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleFeaturedFacilityAction $action): void
    {
        $this->authorize('update', Facility::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة التميز بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $facilities = app(FacilityRepositoryInterface::class)->paginateDashboard();

        return view('livewire.public-facilities.facilities-index', [
            'facilities' => $facilities,
        ]);
    }
}
