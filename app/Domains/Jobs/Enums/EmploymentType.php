<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Enums;

enum EmploymentType: string
{
    case FullTime = 'full_time';
    case PartTime = 'part_time';
    case Contract = 'contract';
    case Temporary = 'temporary';
    case Volunteer = 'volunteer';
    case Internship = 'internship';

    public function label(): string
    {
        return match ($this) {
            self::FullTime => 'دوام كامل',
            self::PartTime => 'دوام جزئي',
            self::Contract => 'عقد',
            self::Temporary => 'مؤقت',
            self::Volunteer => 'تطوع',
            self::Internship => 'تدريب',
        };
    }
}
