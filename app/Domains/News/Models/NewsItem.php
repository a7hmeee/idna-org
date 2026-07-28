<?php

declare(strict_types=1);

namespace App\Domains\News\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Enums\NewsStatus;
use Database\Factories\News\NewsItemFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class NewsItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news_items';

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'category',
        'summary',
        'content',
        'cover_image_path',
        'mobile_image_path',
        'gallery',
        'author',
        'status',
        'is_featured',
        'is_public',
        'publish_at',
        'views_count',
        'display_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'category' => NewsCategory::class,
            'status' => NewsStatus::class,
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'views_count' => 'integer',
            'display_order' => 'integer',
            'publish_at' => 'datetime',
        ];
    }

    protected static function newFactory(): Factory
    {
        return NewsItemFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (NewsItem $news): void {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title_ar);
            }
            $news->display_order ??= 0;
            $news->views_count ??= 0;
        });

        static::updating(function (NewsItem $news): void {
            if (empty($news->slug) && $news->title_ar) {
                $news->slug = Str::slug($news->title_ar);
            }
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

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image_path ? asset('storage/' . $this->cover_image_path) : null;
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->mobile_image_path ? asset('storage/' . $this->mobile_image_path) : null;
    }

    public function scopePublished($query)
    {
        return $query->where('status', NewsStatus::Published)
            ->where('is_public', true)
            ->where('publish_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
