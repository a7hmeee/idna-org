<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\Department\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class ElectronicService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_category_id',
        'department_id',
        'name',
        'slug',
        'summary',
        'description',
        'eligibility',
        'requirements',
        'documents',
        'steps',
        'fees',
        'processing_time',
        'portal_url',
        'requires_login',
        'status',
        'is_public',
        'is_featured',
        'sort_order',
        'views_count',
        'portal_clicks_count',
        'created_by',
        'updated_by',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'requirements' => 'array',
            'documents' => 'array',
            'steps' => 'array',
            'fees' => 'array',
            'requires_login' => 'boolean',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'views_count' => 'integer',
            'portal_clicks_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function views(): HasMany
    {
        return $this->hasMany(ServiceView::class, 'electronic_service_id');
    }

    public function portalClicks(): HasMany
    {
        return $this->hasMany(ServicePortalClick::class, 'electronic_service_id');
    }

    protected static function booted(): void
    {
        self::creating(function (ElectronicService $service): void {
            if (empty($service->slug)) {
                $base = Str::slug($service->name);
                $slug = $base;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$counter++;
                }

                $service->slug = $slug;
            }
        });
    }
}
