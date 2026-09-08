<?php

declare(strict_types=1);

namespace App\Domains\Footer\Models;

use Illuminate\Database\Eloquent\Model;

final class FooterItem extends Model
{
    protected $fillable = [
        'column_key',
        'column_title',
        'label',
        'url',
        'route_name',
        'icon',
        'sort_order',
        'is_active',
        'is_external',
        'target',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_external' => 'boolean',
        ];
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->route_name) {
            try {
                return route($this->route_name);
            } catch (\Throwable) {
                return '#';
            }
        }

        return $this->attributes['url'] ?? null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForColumn($query, string $columnKey)
    {
        return $query->where('column_key', $columnKey);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
