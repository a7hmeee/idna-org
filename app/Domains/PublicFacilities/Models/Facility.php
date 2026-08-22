<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\PublicFacilities\Enums\FacilityStatus;
use Database\Factories\PublicFacilities\FacilityFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'public_facilities';

    protected $fillable = [
        'facility_category_id',
        'name',
        'slug',
        'summary',
        'description',
        'cover_image_path',
        'gallery',
        'phone',
        'email',
        'address',
        'working_hours',
        'services',
        'features',
        'rules',
        'status',
        'is_public',
        'is_featured',
        'display_order',
        'views_count',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => FacilityStatus::class,
            'gallery' => 'array',
            'services' => 'array',
            'features' => 'array',
            'rules' => 'array',
            'display_order' => 'integer',
            'views_count' => 'integer',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function newFactory(): Factory
    {
        return FacilityFactory::new();
    }

    protected static function booted(): void
    {
        self::creating(function (Facility $facility): void {
            if (empty($facility->slug)) {
                $facility->slug = Str::slug($facility->name);
            }
            $facility->display_order ??= 0;
            $facility->views_count ??= 0;
        });

        self::updating(function (Facility $facility): void {
            if (empty($facility->slug) && $facility->name) {
                $facility->slug = Str::slug($facility->name);
            }
            $facility->display_order ??= 0;
            $facility->views_count ??= 0;
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FacilityCategory::class, 'facility_category_id');
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->cover_image_path);
    }

    public function getGalleryUrlsAttribute(): array
    {
        if (! $this->gallery) {
            return [];
        }

        return array_map(fn (string $path) => Storage::disk('public')->url($path), $this->gallery);
    }

    public function scopePublished($query)
    {
        return $query->where('status', FacilityStatus::Published)
            ->where('is_public', true);
    }
}
