<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Enums;

enum ContactRequestStatus: string
{
    case Pending = 'pending';
    case Resolved = 'resolved';
    case Closed = 'closed';
}
