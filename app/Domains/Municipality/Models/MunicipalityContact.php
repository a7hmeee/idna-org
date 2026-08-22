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
 * @property string $type
 * @property string $label
 * @property string|null $value
 * @property string|null $icon
 * @property string|null $url
 * @property int $display_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class MunicipalityContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipality_id',
        'type',
        'label',
        'value',
        'icon',
        'url',
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
