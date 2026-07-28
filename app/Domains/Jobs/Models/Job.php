<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\Department\Models\Department;
use App\Domains\Jobs\Enums\ApplicationMethod;
use App\Domains\Jobs\Enums\EmploymentType;
use App\Domains\Jobs\Enums\JobStatus;
use Database\Factories\Jobs\JobFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'job_offers';

    protected $fillable = [
        'department_id',
        'title',
        'slug',
        'job_number',
        'employment_type',
        'location',
        'salary',
        'vacancies',
        'summary',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'required_documents',
        'application_method',
        'application_url',
        'application_email',
        'application_phone',
        'attachment_path',
        'publish_at',
        'closing_at',
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
            'employment_type' => EmploymentType::class,
            'application_method' => ApplicationMethod::class,
            'status' => JobStatus::class,
            'requirements' => 'array',
            'responsibilities' => 'array',
            'benefits' => 'array',
            'required_documents' => 'array',
            'vacancies' => 'integer',
            'display_order' => 'integer',
            'views_count' => 'integer',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'publish_at' => 'date',
            'closing_at' => 'date',
        ];
    }

    protected static function newFactory(): Factory
    {
        return JobFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (Job $job): void {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title);
            }
            $job->display_order ??= 0;
            $job->views_count ??= 0;
        });

        static::updating(function (Job $job): void {
            if (empty($job->slug) && $job->title) {
                $job->slug = Str::slug($job->title);
            }
            $job->display_order ??= 0;
            $job->views_count ??= 0;
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }

        return asset('storage/' . $this->attachment_path);
    }

    public function scopePublished($query)
    {
        return $query->where('status', JobStatus::Published)
            ->where('is_public', true)
            ->where('publish_at', '<=', now()->toDateString())
            ->where('closing_at', '>=', now()->toDateString());
    }
}
