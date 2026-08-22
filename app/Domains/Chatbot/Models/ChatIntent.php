<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ChatIntent extends Model
{
    protected $fillable = [
        'name',
        'label_ar',
        'description',
        'is_active',
        'minimum_confidence',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'minimum_confidence' => 'decimal:4',
            'sort_order' => 'integer',
        ];
    }

    protected $table = 'chat_intents';

    public function trainingExamples(): HasMany
    {
        return $this->hasMany(ChatTrainingExample::class, 'chat_intent_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
