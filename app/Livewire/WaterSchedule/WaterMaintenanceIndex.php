<?php

declare(strict_types=1);

namespace App\Livewire\WaterSchedule;

use App\Domains\WaterSchedule\Actions\DeleteMaintenanceAction;
use App\Domains\WaterSchedule\Contracts\WaterMaintenanceRepositoryInterface;
use App\Domains\WaterSchedule\Models\WaterMaintenance;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class WaterMaintenanceIndex extends Component
{
    use WithPagination;

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', WaterMaintenance::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteMaintenanceAction $action): void
    {
        $this->authorize('delete', WaterMaintenance::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الصيانة بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $maintenances = app(WaterMaintenanceRepositoryInterface::class)->paginate();

        return view('livewire.water-schedule.water-maintenance-index', [
            'maintenances' => $maintenances,
        ]);
    }
}
