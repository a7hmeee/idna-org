<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Enums;

enum ContactType: string
{
    case Phone = 'phone';
    case Mobile = 'mobile';
    case Fax = 'fax';
    case Email = 'email';
    case Address = 'address';
    case GoogleMaps = 'google_maps';
    case WhatsApp = 'whatsapp';
    case WorkingHours = 'working_hours';
    case Emergency = 'emergency';

    public function label(): string
    {
        return match ($this) {
            self::Phone => 'هاتف',
            self::Mobile => 'جوال',
            self::Fax => 'فاكس',
            self::Email => 'بريد إلكتروني',
            self::Address => 'عنوان',
            self::GoogleMaps => 'خرائط جوجل',
            self::WhatsApp => 'واتساب',
            self::WorkingHours => 'ساعات العمل',
            self::Emergency => 'طوارئ',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
