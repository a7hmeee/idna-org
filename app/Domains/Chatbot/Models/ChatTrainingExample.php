<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Models;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ChatTrainingExample extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'chat_intent_id',
        'text',
        'normalized_text',
        'source',
        'locale',
        'is_active',
        'is_verified',
        'weight',
        'notes',
        'created_by',
    ];

    protected $attributes = [
        'is_active' => true,
        'is_verified' => true,
        'weight' => 1.00,
        'source' => 'seed',
        'locale' => 'ar',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'weight' => 'decimal:2',
        ];
    }

    protected $table = 'chat_training_examples';

    public function intent(): BelongsTo
    {
        return $this->belongsTo(ChatIntent::class, 'chat_intent_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeUsable(Builder $query): Builder
    {
        return $query->active()->verified()->whereNotNull('normalized_text')->where('normalized_text', '!=', '');
    }
}
