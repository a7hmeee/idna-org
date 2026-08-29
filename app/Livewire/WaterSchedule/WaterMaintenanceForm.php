<?php

declare(strict_types=1);

namespace App\Livewire\WaterSchedule;

use App\Domains\WaterSchedule\Actions\CreateMaintenanceAction;
use App\Domains\WaterSchedule\Actions\UpdateMaintenanceAction;
use App\Domains\WaterSchedule\DTOs\WaterMaintenanceData;
use App\Domains\WaterSchedule\Models\WaterMaintenance;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class WaterMaintenanceForm extends Component
{
    public ?int $maintenanceId = null;

    public string $title = '';

    public string $description = '';

    public string $startsAt = '';

    public string $endsAt = '';

    public string $status = 'active';

    public array $affectedAreas = [];

    public bool $isPublic = true;

    public function mount(?WaterMaintenance $maintenance = null): void
    {
        if ($maintenance?->exists) {
            $this->authorize('update', WaterMaintenance::class);

            $this->maintenanceId = $maintenance->id;
            $this->title = $maintenance->title;
            $this->description = $maintenance->description ?? '';
            $this->startsAt = $maintenance->starts_at->format('Y-m-d\TH:i');
            $this->endsAt = $maintenance->ends_at->format('Y-m-d\TH:i');
            $this->status = $maintenance->status;
            $this->affectedAreas = $maintenance->affected_areas ?? [];
            $this->isPublic = $maintenance->is_public;
        } else {
            $this->authorize('create', WaterMaintenance::class);

            $this->startsAt = now()->format('Y-m-d\TH:i');
            $this->endsAt = now()->addHours(2)->format('Y-m-d\TH:i');
        }
    }

    public function save(CreateMaintenanceAction|UpdateMaintenanceAction $action): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'startsAt' => ['required', 'date'],
            'endsAt' => ['required', 'date', 'after_or_equal:startsAt'],
            'status' => ['nullable', 'string', 'max:50'],
            'affectedAreas' => ['nullable', 'array'],
            'affectedAreas.*' => ['string', 'max:255'],
            'isPublic' => ['boolean'],
        ]);

        $dto = WaterMaintenanceData::fromRequest([
            ...$data,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->maintenanceId) {
            $action = app(UpdateMaintenanceAction::class);
            $action->execute($this->maintenanceId, $dto);
            session()->flash('success', 'تم تحديث الصيانة بنجاح.');
        } else {
            $action = app(CreateMaintenanceAction::class);
            $action->execute($dto);
            session()->flash('success', 'تم إضافة الصيانة بنجاح.');
        }

        $this->redirect(route('dashboard.water-schedule.maintenance'), navigate: true);
    }

    public function addAffectedArea(): void
    {
        $this->affectedAreas[] = '';
    }

    public function removeAffectedArea(int $index): void
    {
        unset($this->affectedAreas[$index]);
        $this->affectedAreas = array_values($this->affectedAreas);
    }

    public function render()
    {
        return view('livewire.water-schedule.water-maintenance-form');
    }
}
