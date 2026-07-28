<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Enums;

enum PlatformCategory: string
{
    case ElectronicServices = 'electronic_services';
    case Payments = 'payments';
    case Gis = 'gis';
    case CitizenPortal = 'citizen_portal';
    case Complaints = 'complaints';
    case EmployeePortal = 'employee_portal';
    case Mobile = 'mobile';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ElectronicServices => 'خدمات إلكترونية',
            self::Payments => 'مدفوعات',
            self::Gis => 'نظام معلومات جغرافية',
            self::CitizenPortal => 'بوابة المواطن',
            self::Complaints => 'شكاوى',
            self::EmployeePortal => 'بوابة الموظفين',
            self::Mobile => 'تطبيق جوال',
            self::Other => 'أخرى',
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
