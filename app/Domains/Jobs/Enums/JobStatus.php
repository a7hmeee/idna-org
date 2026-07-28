<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Enums;

enum JobStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Closed = 'closed';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'مسودة',
            self::Published => 'منشور',
            self::Closed => 'مغلق',
            self::Archived => 'مؤرشف',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'warning',
            self::Published => 'success',
            self::Closed => 'danger',
            self::Archived => 'dark',
        };
    }
}
