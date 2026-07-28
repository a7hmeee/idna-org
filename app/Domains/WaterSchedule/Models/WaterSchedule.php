<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class WaterSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'water_area_id',
        'schedule_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'display_order',
        'is_public',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'schedule_date' => 'date',
            'is_public' => 'boolean',
            'display_order' => 'integer',
            'status' => WaterScheduleStatus::class,
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(WaterArea::class, 'water_area_id');
    }
}
