<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Enums;

enum CouncilDecisionType: string
{
    case Administrative = 'administrative';
    case Financial = 'financial';
    case Regulatory = 'regulatory';
    case Service = 'service';
    case Infrastructure = 'infrastructure';
    case Public = 'public';

    public function label(): string
    {
        return match ($this) {
            self::Administrative => 'إداري',
            self::Financial => 'مالي',
            self::Regulatory => 'تنظيمي',
            self::Service => 'خدماتي',
            self::Infrastructure => 'بنية تحتية',
            self::Public => 'عام',
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
