<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Enums;

enum PageCarouselKey: string
{
    case Home = 'home';
    case About = 'about';
    case Services = 'services';
    case Departments = 'departments';
    case Facilities = 'facilities';
    case Jobs = 'jobs';
    case CouncilDecisions = 'council-decisions';
    case CouncilMembers = 'council-members';
    case EngineeringOffices = 'engineering-offices';
    case OpenData = 'open-data';
    case WaterSchedule = 'water-schedule';
    case Announcements = 'announcements';

    public function label(): string
    {
        return match ($this) {
            self::Home => 'الرئيسية',
            self::About => 'عن البلدية',
            self::Services => 'الخدمات الإلكترونية',
            self::Departments => 'دوائر البلدية',
            self::Facilities => 'المرافق العامة',
            self::Jobs => 'الوظائف',
            self::CouncilDecisions => 'قرارات المجلس',
            self::CouncilMembers => 'المجلس البلدي',
            self::EngineeringOffices => 'المكاتب الهندسية',
            self::OpenData => 'البيانات المفتوحة',
            self::WaterSchedule => 'جدول المياه',
            self::Announcements => 'الإعلانات',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    public static function publicPages(): array
    {
        return array_filter(self::cases(), fn ($case) => $case !== self::Home);
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
