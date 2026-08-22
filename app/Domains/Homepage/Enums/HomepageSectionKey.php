<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Enums;

enum HomepageSectionKey: string
{
    case Hero = 'hero';
    case QuickLinks = 'quick_links';
    case MunicipalityIntro = 'municipality_intro';
    case Services = 'services';
    case Departments = 'departments';
    case CouncilMembers = 'council_members';
    case CouncilDecisions = 'council_decisions';
    case EngineeringOffices = 'engineering_offices';
    case Statistics = 'statistics';
    case LatestNews = 'latest_news';
    case Projects = 'projects';
    case Tenders = 'tenders';
    case Announcements = 'announcements';
    case ContactCta = 'contact_cta';

    public function label(): string
    {
        return match ($this) {
            self::Hero => 'الشريط الرئيسي',
            self::QuickLinks => 'الروابط السريعة',
            self::MunicipalityIntro => 'نبذة عن البلدية',
            self::Services => 'الخدمات الإلكترونية',
            self::Departments => 'الدوائر',
            self::CouncilMembers => 'أعضاء المجلس البلدي',
            self::CouncilDecisions => 'قرارات المجلس البلدي',
            self::EngineeringOffices => 'المكاتب الهندسية',
            self::Statistics => 'الإحصائيات',
            self::LatestNews => 'آخر الأخبار',
            self::Projects => 'المشاريع',
            self::Tenders => 'المناقصات',
            self::Announcements => 'الإعلانات',
            self::ContactCta => 'دعوة للتواصل',
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
