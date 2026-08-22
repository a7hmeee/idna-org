<?php

declare(strict_types=1);

namespace App\Livewire\WaterSchedule;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Enums\PageCarouselKey;
use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.home')]
final class PublicWaterSchedule extends Component
{
    public string $selectedAreaId = '';

    public function render()
    {
        $repo = app(WaterScheduleRepositoryInterface::class);
        $pageKey = PageCarouselKey::WaterSchedule->value;

        $slidesRepo = app(HomepageRepositoryInterface::class);
        $slides = $slidesRepo->getPageSlides($pageKey);

        $areas = $repo->getAreas();

        $areaSchedules = [];
        foreach ($areas as $area) {
            $current = $repo->getCurrentSchedule($area->id);
            if ($current === null) {
                $current = $repo->getLatestScheduleForArea($area->id);
            }
            $history = $repo->getScheduleHistory($area->id)->take(7)->toArray();
            $areaSchedules[$area->id] = [
                'area' => $area,
                'current' => $current,
                'history' => $history,
            ];
        }

        $activeMaintenance = null;
        foreach ($areas as $area) {
            $m = $repo->getCurrentMaintenance($area->id);
            if ($m !== null) {
                $activeMaintenance = $m;
                break;
            }
        }

        $todayDayName = $this->getDayName(now()->dayOfWeek);

        return view('livewire.water-schedule.public-water-schedule', [
            'areas' => $areas,
            'areaSchedules' => $areaSchedules,
            'activeMaintenance' => $activeMaintenance,
            'slides' => $slides,
            'todayDayName' => $todayDayName,
        ])->layout('layouts.home', [
            'title' => 'جدول توزيع المياه | بلدية إذنا',
            'metaDescription' => 'تفقد جدول ضخ المياه الأسبوعي في مختلف مناطق بلدية إذنا.',
        ]);
    }

    private function getDayName(int $dayOfWeek): string
    {
        $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

        return $days[$dayOfWeek];
    }
}
