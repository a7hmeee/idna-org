<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Enums;

enum TenderStatus: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';
    case Awarded = 'awarded';
    case Cancelled = 'cancelled';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'مسودة',
            self::Open => 'مفتوح',
            self::Closed => 'مغلق',
            self::Awarded => 'تم الترسية',
            self::Cancelled => 'ملغي',
            self::Archived => 'مؤرشف',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'warning',
            self::Open => 'success',
            self::Closed => 'danger',
            self::Awarded => 'info',
            self::Cancelled => 'dark',
            self::Archived => 'secondary',
        };
    }
}
