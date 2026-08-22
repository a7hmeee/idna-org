<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Enums;

enum CustomFieldType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Number = 'number';
    case Boolean = 'boolean';
    case Email = 'email';
    case Url = 'url';
    case Date = 'date';
    case Color = 'color';
    case Json = 'json';

    public function label(): string
    {
        return match ($this) {
            self::Text => 'نص',
            self::Textarea => 'نص طويل',
            self::Number => 'رقم',
            self::Boolean => 'نعم/لا',
            self::Email => 'بريد إلكتروني',
            self::Url => 'رابط',
            self::Date => 'تاريخ',
            self::Color => 'لون',
            self::Json => ' JSON',
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
