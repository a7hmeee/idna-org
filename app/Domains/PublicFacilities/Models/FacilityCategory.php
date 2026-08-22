<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Models;

use Database\Factories\PublicFacilities\FacilityCategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class FacilityCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    protected static function newFactory(): Factory
    {
        return FacilityCategoryFactory::new();
    }

    protected static function booted(): void
    {
        self::creating(function (FacilityCategory $category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            if (empty($category->display_order)) {
                $category->display_order = static::max('display_order') + 1;
            }
        });

        self::updating(function (FacilityCategory $category): void {
            if (empty($category->slug) && $category->name) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class, 'facility_category_id');
    }
}
