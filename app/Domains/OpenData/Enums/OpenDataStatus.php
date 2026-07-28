<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Enums;

enum OpenDataStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'مسودة',
            self::Published => 'منشور',
            self::Archived => 'مؤرشف',
        };
    }
}
