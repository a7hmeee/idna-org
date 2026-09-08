<?php

declare(strict_types=1);

namespace App\Domains\Navigation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class NavigationItem extends Model
{
    protected $fillable = [
        'label',
        'url',
        'route_name',
        'route_params',
        'icon',
        'parent_id',
        'sort_order',
        'is_active',
        'is_external',
        'target',
        'section',
        'open_in_new_tab',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_external' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'route_params' => 'array',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function activeChildren(): HasMany
    {
        return $this->children()->where('is_active', true);
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->route_name) {
            try {
                $params = $this->route_params ?? [];

                return route($this->route_name, $params);
            } catch (\Throwable) {
                return '#';
            }
        }

        return $this->attributes['url'] ?? null;
    }
}
