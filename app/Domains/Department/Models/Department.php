<?php

declare(strict_types=1);

namespace App\Domains\Department\Models;

use App\Domains\Authentication\Models\User;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $icon
 * @property string|null $cover_image_path
 * @property string|null $manager_name
 * @property string|null $manager_position
 * @property string|null $phone
 * @property string|null $extension
 * @property string|null $mobile
 * @property string|null $email
 * @property string|null $office_location
 * @property string|null $working_hours
 * @property string|null $vision
 * @property string|null $mission
 * @property string|null $responsibilities
 * @property string $status
 * @property int $display_order
 * @property bool $is_public
 * @property bool $is_featured
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'icon',
        'cover_image_path',
        'manager_name',
        'manager_position',
        'phone',
        'extension',
        'mobile',
        'email',
        'office_location',
        'working_hours',
        'vision',
        'mission',
        'responsibilities',
        'status',
        'display_order',
        'is_public',
        'is_featured',
        'created_by',
        'updated_by',
    ];

    protected static function newFactory(): Factory
    {
        return DepartmentFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'integer',
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

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image_path) {
            return null;
        }

        if (Storage::disk('public')->exists($this->cover_image_path)) {
            return asset('storage/'.$this->cover_image_path);
        }

        return null;
    }

    protected static function booted(): void
    {
        self::creating(function (Department $department): void {
            if (empty($department->slug)) {
                $base = Str::slug($department->name);
                $slug = $base;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$counter++;
                }

                $department->slug = $slug;
            }

            if (empty($department->display_order)) {
                $department->display_order = static::max('display_order') + 1;
            }
        });

        self::updating(function (Department $department): void {
            if (empty($department->slug) && $department->name) {
                $department->slug = Str::slug($department->name);
            }
        });
    }
}
