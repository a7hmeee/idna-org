<?php

declare(strict_types=1);

namespace App\Domains\Projects\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\Projects\Enums\ProjectCategory;
use App\Domains\Projects\Enums\ProjectStatus;
use Database\Factories\Projects\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'category',
        'project_status',
        'status',
        'summary',
        'description',
        'start_date',
        'expected_completion_date',
        'actual_completion_date',
        'location',
        'budget',
        'budget_currency',
        'implementation_percentage',
        'contractor',
        'funding_entity',
        'cover_image_path',
        'gallery',
        'documents',
        'is_featured',
        'is_public',
        'display_order',
        'views_count',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'category' => ProjectCategory::class,
            'project_status' => ProjectStatus::class,
            'status' => ProjectStatus::class,
            'gallery' => 'array',
            'documents' => 'array',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'implementation_percentage' => 'integer',
            'views_count' => 'integer',
            'display_order' => 'integer',
            'start_date' => 'date',
            'expected_completion_date' => 'date',
            'actual_completion_date' => 'date',
            'budget' => 'decimal:2',
        ];
    }

    protected static function newFactory(): Factory
    {
        return ProjectFactory::new();
    }

    protected static function booted(): void
    {
        self::creating(function (Project $project): void {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name_ar);
            }
            $project->display_order ??= 0;
            $project->views_count ??= 0;
            $project->implementation_percentage ??= 0;
        });

        self::updating(function (Project $project): void {
            if (empty($project->slug) && $project->name_ar) {
                $project->slug = Str::slug($project->name_ar);
            }
            $project->display_order ??= 0;
            $project->views_count ??= 0;
            $project->implementation_percentage ??= 0;
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
        return $this->cover_image_path ? asset('storage/'.$this->cover_image_path) : null;
    }

    public function getGalleryUrlsAttribute(): array
    {
        if (empty($this->gallery)) {
            return [];
        }

        return array_map(fn (string $path): string => asset('storage/'.$path), $this->gallery);
    }

    public function scopePublished($query)
    {
        return $query->where('status', ProjectStatus::Completed)
            ->where('is_public', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function isVisible(): bool
    {
        return $this->is_public && $this->status === ProjectStatus::Completed;
    }
}
