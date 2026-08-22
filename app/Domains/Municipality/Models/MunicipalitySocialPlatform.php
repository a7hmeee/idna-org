<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $municipality_id
 * @property string $name
 * @property string $slug
 * @property string $icon
 * @property string $url
 * @property string|null $color
 * @property int $display_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class MunicipalitySocialPlatform extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipality_id',
        'name',
        'slug',
        'icon',
        'url',
        'color',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
}
