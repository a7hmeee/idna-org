<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Enums;

enum WorkflowType: string
{
    case Complaint = 'complaint';
    case ContactRequest = 'contact_request';
    case Tracking = 'tracking';

    public function label(): string
    {
        return match ($this) {
            self::Complaint => 'شكوى',
            self::ContactRequest => 'طلب اتصال',
            self::Tracking => 'تتبع طلب',
        };
    }
}
