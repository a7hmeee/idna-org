<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\OpenData\Enums\OpenDataStatus;
use App\Domains\OpenData\Enums\OpenDataType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class OpenDataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'category',
        'description',
        'file_path',
        'file_size',
        'file_format',
        'external_url',
        'status',
        'is_featured',
        'display_order',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => OpenDataType::class,
            'status' => OpenDataStatus::class,
            'is_featured' => 'boolean',
            'display_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    protected static function booting(): void
    {
        self::creating(function (self $dataset): void {
            if (empty($dataset->slug)) {
                $dataset->slug = Str::slug($dataset->title);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', OpenDataStatus::Published);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfType($query, OpenDataType $type)
    {
        return $query->where('type', $type->value);
    }

    public function getDownloadUrlAttribute(): ?string
    {
        if ($this->external_url) {
            return $this->external_url;
        }

        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->url($this->file_path);
        }

        return null;
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (! $this->file_size) {
            return '';
        }

        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1).' '.($units[$i] ?? 'B');
    }
}
