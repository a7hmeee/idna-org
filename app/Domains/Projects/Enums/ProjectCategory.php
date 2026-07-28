<?php

declare(strict_types=1);

namespace App\Domains\Projects\Enums;

enum ProjectCategory: string
{
    case Infrastructure = 'infrastructure';
    case Roads = 'roads';
    case Water = 'water';
    case Sanitation = 'sanitation';
    case Electricity = 'electricity';
    case PublicFacilities = 'public_facilities';
    case Parks = 'parks';
    case Buildings = 'buildings';
    case Technology = 'technology';
    case Culture = 'culture';
    case Sports = 'sports';
    case Health = 'health';
    case Education = 'education';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Infrastructure => 'بنية تحتية',
            self::Roads => 'طرق',
            self::Water => 'مياه',
            self::Sanitation => 'صرف صحي',
            self::Electricity => 'كهرباء',
            self::PublicFacilities => 'مرافق عامة',
            self::Parks => 'حدائق',
            self::Buildings => 'أبنية',
            self::Technology => 'تقنية',
            self::Culture => 'ثقافة',
            self::Sports => 'رياضة',
            self::Health => 'صحة',
            self::Education => 'تعليم',
            self::Other => 'أخرى',
        };
    }
}
