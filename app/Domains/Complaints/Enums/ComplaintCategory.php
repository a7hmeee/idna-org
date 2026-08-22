<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Enums;

enum ComplaintCategory: string
{
    case Service = 'service';
    case Infrastructure = 'infrastructure';
    case Water = 'water';
    case Electricity = 'electricity';
    case Roads = 'roads';
    case Sanitation = 'sanitation';
    case Environment = 'environment';
    case Noise = 'noise';
    case Administrative = 'administrative';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Service => 'خدمات',
            self::Infrastructure => 'بنية تحتية',
            self::Water => 'مياه',
            self::Electricity => 'كهرباء',
            self::Roads => 'طرق',
            self::Sanitation => 'صرف صحي',
            self::Environment => 'بيئة',
            self::Noise => 'ضوضاء',
            self::Administrative => 'إداري',
            self::Other => 'أخرى',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Service => 'info',
            self::Infrastructure => 'accent',
            self::Water => 'blue',
            self::Electricity => 'warning',
            self::Roads => 'dark',
            self::Sanitation => 'danger',
            self::Environment => 'success',
            self::Noise => 'warning',
            self::Administrative => 'info',
            self::Other => 'dark',
        };
    }
}
