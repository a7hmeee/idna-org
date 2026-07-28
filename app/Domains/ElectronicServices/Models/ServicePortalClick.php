<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ServicePortalClick extends Model
{
    protected $fillable = [
        'electronic_service_id',
        'ip_hash',
        'user_agent',
        'referrer',
        'clicked_at',
    ];

    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ElectronicService::class, 'electronic_service_id');
    }
}
