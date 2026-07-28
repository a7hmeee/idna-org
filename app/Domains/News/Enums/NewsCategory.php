<?php

declare(strict_types=1);

namespace App\Domains\News\Enums;

enum NewsCategory: string
{
    case General = 'general';
    case Municipal = 'municipal';
    case Council = 'council';
    case Community = 'community';
    case Events = 'events';
    case Announcements = 'announcements';
    case Projects = 'projects';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::General => 'عام',
            self::Municipal => 'بلدي',
            self::Council => 'مجلس بلدي',
            self::Community => 'مجتمعي',
            self::Events => 'فعاليات',
            self::Announcements => 'إعلانات',
            self::Projects => 'مشاريع',
            self::Other => 'أخرى',
        };
    }
}
