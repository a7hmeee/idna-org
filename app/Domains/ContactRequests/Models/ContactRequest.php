<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Models;

use App\Domains\ContactRequests\Enums\ContactRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class ContactRequest extends Model
{
    protected $fillable = [
        'tracking_number',
        'name',
        'email',
        'phone',
        'message',
        'internal_notes',
        'response_notes',
        'assigned_to',
        'assigned_department',
        'department',
        'status',
        'source',
        'session_id',
        'user_id',
        'submitted_at',
        'resolved_at',
        'in_progress_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContactRequestStatus::class,
            'submitted_at' => 'datetime',
            'resolved_at' => 'datetime',
            'in_progress_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        self::creating(function (ContactRequest $contactRequest): void {
            if (empty($contactRequest->tracking_number)) {
                $contactRequest->tracking_number = 'CR-'.strtoupper(Str::random(10));
            }
            $contactRequest->status ??= ContactRequestStatus::Pending;
            $contactRequest->submitted_at ??= now();
        });
    }
}
