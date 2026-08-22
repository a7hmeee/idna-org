<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $key
 * @property string|null $title
 * @property string|null $subtitle
 * @property bool $is_enabled
 * @property int $sort_order
 * @property int|null $items_limit
 * @property array|null $settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'is_enabled',
        'sort_order',
        'items_limit',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'sort_order' => 'integer',
            'items_limit' => 'integer',
            'settings' => 'array',
        ];
    }
}
