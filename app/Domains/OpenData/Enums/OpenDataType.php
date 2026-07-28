<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Enums;

enum OpenDataType: string
{
    case Dataset = 'datasets';
    case Report = 'reports';

    public function label(): string
    {
        return match ($this) {
            self::Dataset => 'مجموعة بيانات',
            self::Report => 'تقرير',
        };
    }
}
