<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Enums;

enum AnnouncementPriority: string
{
    // === New 3 cases ===
    case Normal = 'normal';
    case Important = 'important';
    case Urgent = 'urgent';

    // === Legacy values — keep for existing DB records ===
    case LegacyLow = 'low';
    case LegacyHigh = 'high';

    public function label(): string
    {
        return match ($this) {
            self::Normal, self::LegacyLow => 'عادية',
            self::Important, self::LegacyHigh => 'مهمة',
            self::Urgent => 'عاجلة',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Normal, self::LegacyLow => 'info',
            self::Important, self::LegacyHigh => 'warning',
            self::Urgent => 'danger',
        };
    }
}
