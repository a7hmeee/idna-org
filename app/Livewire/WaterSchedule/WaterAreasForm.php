<?php

declare(strict_types=1);

namespace App\Livewire\WaterSchedule;

use App\Domains\WaterSchedule\Actions\CreateWaterAreaAction;
use App\Domains\WaterSchedule\Actions\UpdateWaterAreaAction;
use App\Domains\WaterSchedule\DTOs\WaterAreaData;
use App\Domains\WaterSchedule\Models\WaterArea;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class WaterAreasForm extends Component
{
    public ?int $areaId = null;
    public string $name = '';
    public string $description = '';
    public int $displayOrder = 0;
    public bool $isActive = true;

    public function mount(?WaterArea $waterArea = null): void
    {
        if ($waterArea && $waterArea->exists) {
            $this->authorize('update', WaterArea::class);

            $this->areaId = $waterArea->id;
            $this->name = $waterArea->name;
            $this->description = $waterArea->description ?? '';
            $this->displayOrder = $waterArea->display_order;
            $this->isActive = $waterArea->is_active;
        } else {
            $this->authorize('create', WaterArea::class);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'displayOrder' => ['nullable', 'integer', 'min:0'],
            'isActive' => ['boolean'],
        ]);

        $data['displayOrder'] ??= 0;

        $dto = WaterAreaData::fromRequest([
            ...$data,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->areaId) {
            app(UpdateWaterAreaAction::class)->execute($this->areaId, $dto);
            session()->flash('success', 'تم تحديث المنطقة بنجاح.');
        } else {
            app(CreateWaterAreaAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة المنطقة بنجاح.');
        }

        $this->redirect(route('dashboard.water-schedule.areas'), navigate: true);
    }

    public function render()
    {
        return view('livewire.water-schedule.water-areas-form');
    }
}
