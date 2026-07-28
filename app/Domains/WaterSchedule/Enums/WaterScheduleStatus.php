<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Enums;

enum WaterScheduleStatus: string
{
    case Available = 'available';
    case LowPressure = 'low_pressure';
    case Maintenance = 'maintenance';
    case Emergency = 'emergency';
    case NoWater = 'no_water';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'يوجد ضخ',
            self::LowPressure => 'ضغط منخفض',
            self::Maintenance => 'صيانة',
            self::Emergency => 'طارئ',
            self::NoWater => 'لا يوجد ضخ',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Available => 'success',
            self::LowPressure => 'warning',
            self::Maintenance => 'orange',
            self::Emergency => 'danger',
            self::NoWater => 'dark',
        };
    }
}
