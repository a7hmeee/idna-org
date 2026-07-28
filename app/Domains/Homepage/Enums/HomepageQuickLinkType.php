<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Enums;

enum HomepageQuickLinkType: string
{
    case Internal = 'internal';
    case External = 'external';
    case Service = 'service';
    case Portal = 'portal';
    case Contact = 'contact';
    case Department = 'department';

    public function label(): string
    {
        return match ($this) {
            self::Internal => 'داخلي',
            self::External => 'خارجي',
            self::Service => 'خدمة',
            self::Portal => 'بوابة',
            self::Contact => 'اتصال',
            self::Department => 'قسم',
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
