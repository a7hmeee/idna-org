<?php

declare(strict_types=1);

namespace App\Livewire\WaterSchedule;

use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use App\Domains\Homepage\Enums\PageCarouselKey;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.home')]
final class PublicWaterSchedule extends Component
{
    public string $selectedAreaId = '';

    public function updatedSelectedAreaId(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function currentSchedule(): ?\App\Domains\WaterSchedule\Models\WaterSchedule
    {
        if (!$this->selectedAreaId) {
            return null;
        }

        return app(WaterScheduleRepositoryInterface::class)->getCurrentSchedule((int) $this->selectedAreaId);
    }

    #[Computed]
    public function todayDayName(): string
    {
        $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        return $days[now()->dayOfWeek];
    }

    #[Computed]
    public function latestUpdate(): ?string
    {
        return now()->format('Y-m-d h:i A');
    }

    public function render()
    {
        $repo = app(WaterScheduleRepositoryInterface::class);
        $pageKey = PageCarouselKey::WaterSchedule->value;

        $slidesRepo = app(\App\Domains\Homepage\Contracts\HomepageRepositoryInterface::class);
        $slides = $slidesRepo->getPageSlides($pageKey);

        $areas = $repo->getAreas();
        $activeMaintenance = null;

        if ($this->selectedAreaId) {
            $areaId = (int) $this->selectedAreaId;
            $activeMaintenance = $repo->getCurrentMaintenance($areaId);
        }

        return view('livewire.water-schedule.public-water-schedule', [
            'areas' => $areas,
            'activeMaintenance' => $activeMaintenance,
            'slides' => $slides,
        ])->layout('layouts.home', [
            'title' => 'جدول المياه',
            'metaDescription' => 'تفقد جدول الضخ الأسبوعي للمياه في مختلف مناطق بلدية إذنا.',
        ]);
    }
}