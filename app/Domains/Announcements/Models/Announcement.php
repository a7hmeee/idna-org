<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Models;

use App\Domains\Announcements\Enums\AnnouncementPriority;
use App\Domains\Announcements\Enums\AnnouncementStatus;
use App\Domains\Announcements\Enums\AnnouncementType;
use App\Domains\Authentication\Models\User;
use Database\Factories\Announcements\AnnouncementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'priority',
        'status',
        'short_description',
        'content',
        'desktop_image_path',
        'mobile_image_path',
        'attachment_path',
        'external_url',
        'is_featured',
        'display_order',
        'views',
        'starts_at',
        'ends_at',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => AnnouncementType::class,
            'priority' => AnnouncementPriority::class,
            'status' => AnnouncementStatus::class,
            'is_featured' => 'boolean',
            'display_order' => 'integer',
            'views' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    protected static function newFactory(): AnnouncementFactory
    {
        return AnnouncementFactory::new();
    }

    protected static function booted(): void
    {
        self::creating(function (Announcement $announcement): void {
            if (empty($announcement->slug)) {
                $announcement->slug = Str::slug($announcement->title);
            }
            $announcement->display_order ??= 0;
            $announcement->views ??= 0;
        });

        self::updating(function (Announcement $announcement): void {
            if (empty($announcement->slug) && $announcement->title) {
                $announcement->slug = Str::slug($announcement->title);
            }
            $announcement->display_order ??= 0;
            $announcement->views ??= 0;
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

    public function getImageUrlAttribute(): ?string
    {
        return $this->desktop_image_path ? asset('storage/'.$this->desktop_image_path) : null;
    }

    public function scopePublished($query)
    {
        return $query->where('status', AnnouncementStatus::Published)
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', AnnouncementPriority::Urgent);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function isVisible(): bool
    {
        return $this->status === AnnouncementStatus::Published
            && $this->published_at?->lte(now());
    }
}
