<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Models;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class EngineeringOffice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'office_name',
        'slug',
        'engineer_name',
        'license_number',
        'phone',
        'mobile',
        'email',
        'address',
        'specializations',
        'approval_status',
        'status',
        'notes',
        'is_public',
        'sort_order',
        'created_by',
        'updated_by',
        'approved_at',
        'suspended_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'specializations' => 'array',
            'is_public' => 'boolean',
            'sort_order' => 'integer',
            'approved_at' => 'datetime',
            'suspended_at' => 'datetime',
            'expires_at' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted(): void
    {
        self::creating(function (EngineeringOffice $office): void {
            if (empty($office->slug)) {
                $base = Str::slug($office->office_name);
                $slug = $base;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$counter++;
                }

                $office->slug = $slug;
            }
        });
    }
}
