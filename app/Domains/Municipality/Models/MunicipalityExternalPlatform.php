<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $municipality_id
 * @property string $name
 * @property string|null $description
 * @property string $icon
 * @property string $url
 * @property string|null $category
 * @property string|null $color
 * @property bool $open_in_new_tab
 * @property bool $is_featured
 * @property int $display_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class MunicipalityExternalPlatform extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipality_id',
        'name',
        'description',
        'icon',
        'url',
        'category',
        'color',
        'open_in_new_tab',
        'is_featured',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'open_in_new_tab' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
}
