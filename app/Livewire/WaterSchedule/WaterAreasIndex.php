<?php

declare(strict_types=1);

namespace App\Livewire\WaterSchedule;

use App\Domains\WaterSchedule\Actions\DeleteWaterAreaAction;
use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;
use App\Domains\WaterSchedule\Models\WaterArea;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class WaterAreasIndex extends Component
{
    use WithPagination;

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', WaterArea::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteWaterAreaAction $action): void
    {
        $this->authorize('delete', WaterArea::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف المنطقة بنجاح.');
    }

    public function toggleActive(int $id, WaterAreaRepositoryInterface $repository): void
    {
        $this->authorize('update', WaterArea::class);

        $repository->toggleActive($id);

        session()->flash('success', 'تم تغيير حالة المنطقة بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $areas = app(WaterAreaRepositoryInterface::class)->paginateDashboard();

        return view('livewire.water-schedule.water-areas-index', [
            'areas' => $areas,
        ]);
    }
}
