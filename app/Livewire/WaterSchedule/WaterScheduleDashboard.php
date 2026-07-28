<?php

declare(strict_types=1);

namespace App\Livewire\WaterSchedule;

use App\Domains\WaterSchedule\Actions\CopyPreviousScheduleAction;
use App\Domains\WaterSchedule\Actions\PublishWaterScheduleAction;
use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;
use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;
use App\Domains\WaterSchedule\Models\WaterArea;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class WaterScheduleDashboard extends Component
{
    public string $date;
    public array $scheduleItems = [];

    public function mount(): void
    {
        $this->date = now()->toDateString();
        $this->loadSchedule();
    }

    public function updatedDate(): void
    {
        $this->loadSchedule();
    }

    public function loadSchedule(): void
    {
        $areas = app(WaterAreaRepositoryInterface::class)->getActiveAreas();
        $schedules = app(WaterScheduleRepositoryInterface::class)->getByDate($this->date);

        $scheduleMap = $schedules->keyBy('water_area_id');

        $this->scheduleItems = $areas->map(function (WaterArea $area) use ($scheduleMap) {
            $schedule = $scheduleMap->get($area->id);

            return [
                'area_id' => $area->id,
                'area_name' => $area->name,
                'start_time' => $schedule?->start_time ?? '',
                'end_time' => $schedule?->end_time ?? '',
                'status' => $schedule?->status?->value ?? WaterScheduleStatus::Available->value,
                'notes' => $schedule?->notes ?? '',
            ];
        })->toArray();
    }

    public function copyPreviousDay(CopyPreviousScheduleAction $action): void
    {
        $this->authorize('create', WaterArea::class);

        $count = $action->execute($this->date, auth()->id());

        if ($count > 0) {
            session()->flash('success', "تم نسخ {$count} سجل من جدول الأمس.");
        } else {
            session()->flash('info', 'لا يوجد جدول لليوم السابق.');
        }

        $this->loadSchedule();
    }

    public function save(): void
    {
        $this->authorize('update', WaterArea::class);

        $repository = app(WaterScheduleRepositoryInterface::class);

        foreach ($this->scheduleItems as $item) {
            $repository->upsert([
                'water_area_id' => $item['area_id'],
                'schedule_date' => $this->date,
                'start_time' => $item['start_time'] ?: null,
                'end_time' => $item['end_time'] ?: null,
                'status' => $item['status'],
                'notes' => $item['notes'] ?: null,
                'display_order' => 0,
                'is_public' => true,
                'updated_by' => auth()->id(),
            ]);
        }

        session()->flash('success', 'تم حفظ جدول توزيع المياه بنجاح.');
    }

    public function publish(PublishWaterScheduleAction $action): void
    {
        $this->authorize('publish', WaterArea::class);

        $action->execute($this->date, auth()->id());

        session()->flash('success', 'تم نشر الجدول بنجاح.');
    }

    public function render()
    {
        $areas = app(WaterAreaRepositoryInterface::class)->getActiveAreas();
        $statuses = WaterScheduleStatus::cases();

        return view('livewire.water-schedule.water-schedule-dashboard', [
            'areas' => $areas,
            'statuses' => $statuses,
        ]);
    }
}
