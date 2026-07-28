<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Enums;

enum ComplaintPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Urgent = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'منخفضة',
            self::Medium => 'متوسطة',
            self::High => 'عالية',
            self::Urgent => 'عاجلة',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Low => 'info',
            self::Medium => 'warning',
            self::High => 'danger',
            self::Urgent => 'dark',
        };
    }
}