<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Models;

use Illuminate\Database\Eloquent\Model;

final class EmploymentTypeLabel extends Model
{
    protected $fillable = [
        'type_key',
        'label_ar',
        'label_en',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public static function getLabelForKey(string $key): string
    {
        $label = self::where('type_key', $key)->where('is_active', true)->first();

        if ($label) {
            return $label->label_ar;
        }

        return match ($key) {
            'full_time' => 'دوام كامل',
            'part_time' => 'دوام جزئي',
            'contract' => 'عقد',
            'temporary' => 'مؤقت',
            'volunteer' => 'تطوع',
            'internship' => 'تدريب',
            default => $key,
        };
    }

    public static function getAllActive(): array
    {
        return self::active()->ordered()->get()->mapWithKeys(fn ($l) => [
            $l->type_key => $l->label_ar,
        ])->toArray();
    }
}
