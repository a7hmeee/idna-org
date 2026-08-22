<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\Tenders\Enums\TenderStatus;
use Database\Factories\Tenders\TenderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class Tender extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenders';

    protected $fillable = [
        'tender_number',
        'title_ar',
        'title_en',
        'slug',
        'summary',
        'description',
        'category',
        'issuing_department',
        'publication_date',
        'submission_deadline',
        'opening_date',
        'status',
        'eligibility_requirements',
        'application_instructions',
        'contact_info',
        'contact_phone',
        'contact_email',
        'tender_documents',
        'result_documents',
        'budget',
        'budget_currency',
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
            'status' => TenderStatus::class,
            'eligibility_requirements' => 'array',
            'application_instructions' => 'array',
            'tender_documents' => 'array',
            'result_documents' => 'array',
            'publication_date' => 'date',
            'submission_deadline' => 'date',
            'opening_date' => 'date',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'display_order' => 'integer',
            'views_count' => 'integer',
        ];
    }

    protected static function newFactory(): Factory
    {
        return TenderFactory::new();
    }

    protected static function booted(): void
    {
        self::creating(function (Tender $tender): void {
            if (empty($tender->slug)) {
                $tender->slug = Str::slug($tender->title_ar);
            }
            $tender->display_order ??= 0;
            $tender->views_count ??= 0;
        });

        self::updating(function (Tender $tender): void {
            if (empty($tender->slug) && $tender->title_ar) {
                $tender->slug = Str::slug($tender->title_ar);
            }
            $tender->display_order ??= 0;
            $tender->views_count ??= 0;
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

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', TenderStatus::Open)
            ->where('is_public', true)
            ->where('publication_date', '<=', now()->toDateString())
            ->where('submission_deadline', '>=', now()->toDateString());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('submission_deadline', '>=', now()->toDateString());
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('submission_deadline', '<', now()->toDateString());
    }
}
