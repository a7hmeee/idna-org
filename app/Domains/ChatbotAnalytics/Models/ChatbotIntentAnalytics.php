<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Models;

use App\Domains\Chatbot\Models\ChatbotConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChatbotIntentAnalytics extends Model
{
    protected $fillable = [
        'conversation_id',
        'message_id',
        'predicted_intent',
        'final_intent',
        'confidence',
        'prediction_source',
        'handler_used',
        'execution_time_ms',
        'clarification_happened',
        'is_unknown',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'decimal:4',
            'clarification_happened' => 'boolean',
            'is_unknown' => 'boolean',
            'execution_time_ms' => 'integer',
        ];
    }

    protected $table = 'chatbot_intent_analytics';

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatbotConversation::class, 'conversation_id');
    }
}
