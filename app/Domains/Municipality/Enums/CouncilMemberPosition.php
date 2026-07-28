<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Enums;

enum CouncilMemberPosition: string
{
    case Mayor = 'mayor';
    case DeputyMayor = 'deputy_mayor';
    case CouncilMember = 'council_member';
    case Secretary = 'secretary';
    case Treasurer = 'treasurer';

    public function label(): string
    {
        return match ($this) {
            self::Mayor => 'رئيس المجلس',
            self::DeputyMayor => 'نائب الرئيس',
            self::CouncilMember => 'عضو مجلس',
            self::Secretary => 'سكرتير',
            self::Treasurer => 'أمين صندوق',
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
