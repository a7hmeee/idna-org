<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Enums;

enum ComplaintStatus: string
{
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Rejected = 'rejected';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'مقدمة',
            self::UnderReview => 'قيد المراجعة',
            self::Assigned => 'تم التعيين',
            self::InProgress => 'قيد المعالجة',
            self::Resolved => 'تم الحل',
            self::Rejected => 'مرفوضة',
            self::Closed => 'مغلقة',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Submitted => 'info',
            self::UnderReview => 'warning',
            self::Assigned => 'accent',
            self::InProgress => 'blue',
            self::Resolved => 'success',
            self::Rejected => 'danger',
            self::Closed => 'dark',
        };
    }
}
