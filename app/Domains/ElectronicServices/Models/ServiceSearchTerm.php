<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ServiceSearchTerm extends Model
{
    protected $fillable = [
        'electronic_service_id',
        'term',
        'normalized_term',
        'type',
        'weight',
        'priority',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'integer',
            'priority' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected $table = 'service_search_terms';

    public function service(): BelongsTo
    {
        return $this->belongsTo(ElectronicService::class, 'electronic_service_id');
    }
}
