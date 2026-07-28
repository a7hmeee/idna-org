<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Enums;

enum AnnouncementType: string
{
    // === New 10 cases ===
    case General = 'general';
    case ImportantNotice = 'important_notice';
    case Emergency = 'emergency';
    case ServiceInterruption = 'service_interruption';
    case WaterNotice = 'water_notice';
    case RoadClosure = 'road_closure';
    case PublicInvitation = 'public_invitation';
    case TenderNotice = 'tender_notice';
    case Event = 'event';
    case Other = 'other';

    // === Legacy values — keep for existing DB records ===
    case LegacyInformational = 'informational';
    case LegacyWarning = 'warning';
    case LegacyUrgentType = 'urgent';
    case LegacyHoliday = 'holiday';
    case LegacyTender = 'tender';

    public function label(): string
    {
        return match ($this) {
            self::General, self::LegacyInformational => 'إعلان عام',
            self::ImportantNotice, self::LegacyWarning => 'إشعار مهم',
            self::Emergency => 'طوارئ',
            self::ServiceInterruption => 'انقطاع خدمة',
            self::WaterNotice => 'إشعار مياه',
            self::RoadClosure => 'إغلاق طريق',
            self::PublicInvitation => 'دعوة عامة',
            self::TenderNotice, self::LegacyTender => 'إشعار مناقصة',
            self::Event => 'فعالية',
            self::Other, self::LegacyHoliday => 'أخرى',
            self::LegacyUrgentType => 'عاجل',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::General, self::LegacyInformational => 'info',
            self::ImportantNotice, self::LegacyWarning => 'warning',
            self::Emergency, self::LegacyUrgentType => 'danger',
            self::ServiceInterruption => 'orange',
            self::WaterNotice => 'blue',
            self::RoadClosure => 'amber',
            self::PublicInvitation => 'purple',
            self::TenderNotice, self::LegacyTender => 'success',
            self::Event => 'accent',
            self::Other, self::LegacyHoliday => 'gray',
        };
    }
}
