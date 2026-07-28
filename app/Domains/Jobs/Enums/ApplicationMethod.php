<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Enums;

enum ApplicationMethod: string
{
    case ExternalLink = 'external_link';
    case Email = 'email';
    case Phone = 'phone';
    case Office = 'office';
    case DownloadForm = 'download_form';

    public function label(): string
    {
        return match ($this) {
            self::ExternalLink => 'رابط خارجي',
            self::Email => 'بريد إلكتروني',
            self::Phone => 'هاتف',
            self::Office => 'مراجعة البلدية',
            self::DownloadForm => 'تحميل استمارة',
        };
    }
}
