<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\Complaints\Enums\ComplaintCategory;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Department\Models\Department;
use Database\Factories\Complaints\ComplaintFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'complaint_number',
        'tracking_number',
        'citizen_name',
        'phone',
        'email',
        'national_id',
        'category',
        'department_id',
        'subject',
        'description',
        'location',
        'latitude',
        'longitude',
        'attachments',
        'priority',
        'status',
        'internal_notes',
        'public_response',
        'assigned_to',
        'submitted_by',
        'submitted_at',
        'resolution_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'category' => ComplaintCategory::class,
            'priority' => ComplaintPriority::class,
            'status' => ComplaintStatus::class,
            'attachments' => 'array',
            'latitude' => 'float',
            'longitude' => 'float',
            'submitted_at' => 'datetime',
            'resolution_at' => 'datetime',
        ];
    }

    protected static function newFactory(): Factory
    {
        return ComplaintFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (Complaint $complaint): void {
            if (empty($complaint->tracking_number)) {
                $complaint->tracking_number = 'CMP-' . strtoupper(Str::random(10));
            }
            $complaint->status ??= ComplaintStatus::Submitted;
            $complaint->priority ??= ComplaintPriority::Medium;
            $complaint->submitted_at ??= now();
        });
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getAttachmentsUrlsAttribute(): array
    {
        if (empty($this->attachments)) {
            return [];
        }

        return array_map(fn (string $path): string => asset('storage/' . $path), $this->attachments);
    }

    public function scopeByStatus($query, ComplaintStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByPriority($query, ComplaintPriority $priority)
    {
        return $query->where('priority', $priority);
    }
}