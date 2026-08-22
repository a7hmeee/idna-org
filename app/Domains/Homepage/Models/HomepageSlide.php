<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\Homepage\Enums\PageCarouselKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $page_key
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $description
 * @property string|null $image_path
 * @property string|null $mobile_image_path
 * @property string|null $button_text
 * @property string|null $button_url
 * @property string|null $secondary_button_text
 * @property string|null $secondary_button_url
 * @property string|null $badge_text
 * @property bool $is_active
 * @property int $sort_order
 * @property string|null $starts_at
 * @property string|null $ends_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class HomepageSlide extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['image_url', 'mobile_image_url'];

    protected $fillable = [
        'page_key',
        'title',
        'subtitle',
        'description',
        'image_path',
        'mobile_image_path',
        'button_text',
        'button_url',
        'secondary_button_text',
        'secondary_button_url',
        'badge_text',
        'is_active',
        'sort_order',
        'starts_at',
        'ends_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
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

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return asset('storage/'.$this->image_path);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        if (! $this->mobile_image_path) {
            return null;
        }

        return asset('storage/'.$this->mobile_image_path);
    }

    public function scopeForPage(Builder $query, string $pageKey): Builder
    {
        return $query->where('page_key', $pageKey);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentlyVisible(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
        })->where(function (Builder $q): void {
            $q->whereNull('ends_at')
                ->orWhere('ends_at', '>=', now());
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function getPageLabelAttribute(): string
    {
        $key = PageCarouselKey::tryFrom($this->page_key);

        return $key?->label() ?? $this->page_key;
    }
}
