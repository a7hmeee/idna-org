<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $hourable_id
 * @property string $hourable_type
 * @property string $day
 * @property string|null $opening_time
 * @property string|null $closing_time
 * @property bool $is_closed
 * @property int $display_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class BusinessHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'hourable_id',
        'hourable_type',
        'day',
        'opening_time',
        'closing_time',
        'is_closed',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_closed' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function hourable(): MorphTo
    {
        return $this->morphTo();
    }
}
