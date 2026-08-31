<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Enums;

enum MediaCollection: string
{
    case Logo = 'logo';
    case WhiteLogo = 'white_logo';
    case DarkLogo = 'dark_logo';
    case Favicon = 'favicon';
    case Hero = 'hero';
    case Cover = 'cover';
    case Banner = 'banner';
    case Gallery = 'gallery';
    case Images = 'images';
    case StatisticsBg = 'statistics_bg';
    case PortalCta = 'portal-cta';
    case DepartmentsHero = 'departments-hero';
    case CouncilHero = 'council-hero';
    case DecisionsHero = 'decisions-hero';
    case AboutImage = 'about_image';
    case MobileLogo = 'mobile_logo';
    case ChatbotAvatar = 'chatbot_avatar';
    case Attachment = 'attachment';

    case News = 'news';
    case Announcements = 'announcements';
    case Projects = 'projects';
    case ProjectGallery = 'project_gallery';
    case CouncilMembers = 'council_members';
    case Departments = 'departments';
    case Facilities = 'facilities';
    case FacilityGallery = 'facility_gallery';
    case Services = 'services';
    case PartnerLogo = 'partner_logo';
    case PageCarousel = 'page_carousel';
    case Mayor = 'mayor';

    public function label(): string
    {
        return match ($this) {
            self::Logo => 'الشعار الأساسي',
            self::WhiteLogo => 'الشعار الأبيض',
            self::DarkLogo => 'الشعار الداكن',
            self::Favicon => 'أيقونة الموقع',
            self::Hero => 'صورة الهيرو',
            self::Cover => 'صورة الغلاف',
            self::Banner => 'بانر',
            self::Gallery => 'معرض الصور',
            self::Images => 'صور تعريفية',
            self::StatisticsBg => 'خلفية الإحصائيات',
            self::PortalCta => 'خلفية قسم الخدمات',
            self::DepartmentsHero => 'صور كاروسيل الأقسام',
            self::CouncilHero => 'صور كاروسيل المجلس البلدي',
            self::DecisionsHero => 'خلفية هيرو القرارات',
            self::AboutImage => 'صورة نبذة عن البلدية',
            self::MobileLogo => 'شعار الجوال',
            self::ChatbotAvatar => 'صورة المساعد الآلي',
            self::Attachment => 'مرفق',
            self::News => 'صور الأخبار',
            self::Announcements => 'صور الإعلانات',
            self::Projects => 'صور المشاريع',
            self::ProjectGallery => 'معرض صور المشاريع',
            self::CouncilMembers => 'صور أعضاء المجلس',
            self::Departments => 'صور الأقسام',
            self::Facilities => 'صور المرافق',
            self::FacilityGallery => 'معرض صور المرافق',
            self::Services => 'صور تصنيفات الخدمات',
            self::PartnerLogo => 'شعارات الشركاء',
            self::PageCarousel => 'صور كاروسيل الصفحات',
            self::Mayor => 'صورة العمدة',
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
