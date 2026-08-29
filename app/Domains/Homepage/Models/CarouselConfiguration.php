<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $page
 * @property string|null $section
 * @property string $type
 * @property bool $is_active
 * @property int $sort_order
 * @property int $desktop_slides
 * @property int $tablet_slides
 * @property int $mobile_slides
 * @property bool $autoplay
 * @property int $autoplay_delay
 * @property bool $loop
 * @property bool $show_navigation
 * @property bool $show_pagination
 * @property bool $pause_on_hover
 * @property string $direction
 * @property string $transition
 */
final class CarouselConfiguration extends Model
{
    protected $fillable = [
        'key',
        'name',
        'title',
        'subtitle',
        'page',
        'section',
        'type',
        'is_active',
        'sort_order',
        'desktop_slides',
        'tablet_slides',
        'mobile_slides',
        'autoplay',
        'autoplay_delay',
        'loop',
        'show_navigation',
        'show_pagination',
        'pause_on_hover',
        'direction',
        'transition',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'desktop_slides' => 'integer',
            'tablet_slides' => 'integer',
            'mobile_slides' => 'integer',
            'autoplay' => 'boolean',
            'autoplay_delay' => 'integer',
            'loop' => 'boolean',
            'show_navigation' => 'boolean',
            'show_pagination' => 'boolean',
            'pause_on_hover' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page);
    }

    /**
     * Get configuration as an array for frontend consumption.
     */
    public function toConfigArray(): array
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'desktop_slides' => $this->desktop_slides,
            'tablet_slides' => $this->tablet_slides,
            'mobile_slides' => $this->mobile_slides,
            'autoplay' => $this->autoplay,
            'autoplay_delay' => $this->autoplay_delay,
            'loop' => $this->loop,
            'show_navigation' => $this->show_navigation,
            'show_pagination' => $this->show_pagination,
            'pause_on_hover' => $this->pause_on_hover,
            'direction' => $this->direction,
            'transition' => $this->transition,
        ];
    }
}
