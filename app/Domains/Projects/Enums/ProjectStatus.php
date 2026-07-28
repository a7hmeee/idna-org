<?php

declare(strict_types=1);

namespace App\Domains\Projects\Enums;

enum ProjectStatus: string
{
    case Planned = 'planned';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::Planned => 'مخطط',
            self::InProgress => 'قيد التنفيذ',
            self::Completed => 'منجز',
            self::Suspended => 'معلق',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planned => 'info',
            self::InProgress => 'warning',
            self::Completed => 'success',
            self::Suspended => 'danger',
        };
    }
}
