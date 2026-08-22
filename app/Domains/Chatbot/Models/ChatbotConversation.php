<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Models;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ChatbotConversation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'session_id',
        'user_id',
        'status',
        'metadata',
        'current_service_id',
        'current_service_name',
        'last_intent',
        'previous_intent',
        'context_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'context_updated_at' => 'datetime',
        ];
    }

    protected $table = 'chatbot_conversations';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotMessage::class, 'conversation_id');
    }
}
