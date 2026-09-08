<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Models;

use Illuminate\Database\Eloquent\Model;

final class WaterStatusLabel extends Model
{
    protected $fillable = [
        'status_key',
        'label_ar',
        'label_en',
        'color',
        'bg_color',
        'dot_color',
        'icon',
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

    public static function getLabelForKey(string $key): array
    {
        $label = self::where('status_key', $key)->where('is_active', true)->first();

        if ($label) {
            return [
                'label' => $label->label_ar,
                'color' => $label->color,
                'bg' => $label->bg_color,
                'dot' => $label->dot_color,
            ];
        }

        return match ($key) {
            'available' => ['label' => 'متوفر', 'color' => '#176B32', 'bg' => '#EAF5EE', 'dot' => '#176B32'],
            'low_pressure' => ['label' => 'ضغط منخفض', 'color' => '#B45309', 'bg' => '#FEF3C7', 'dot' => '#B45309'],
            'maintenance' => ['label' => 'صيانة', 'color' => '#B45309', 'bg' => '#FEF3C7', 'dot' => '#B45309'],
            'emergency' => ['label' => 'طارئ', 'color' => '#DC2626', 'bg' => '#FEE2E2', 'dot' => '#DC2626'],
            'no_water' => ['label' => 'مقطوع', 'color' => '#6B7280', 'bg' => '#F3F4F6', 'dot' => '#D1D5DB'],
            default => ['label' => $key, 'color' => '#6B7280', 'bg' => '#F3F4F6', 'dot' => '#D1D5DB'],
        };
    }

    public static function getAllActive(): array
    {
        return self::active()->ordered()->get()->mapWithKeys(fn ($l) => [
            $l->status_key => [
                'label' => $l->label_ar,
                'color' => $l->color,
                'bg' => $l->bg_color,
                'dot' => $l->dot_color,
            ],
        ])->toArray();
    }
}
