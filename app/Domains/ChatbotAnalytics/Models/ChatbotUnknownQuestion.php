<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Models;

use App\Domains\Chatbot\Models\ChatbotConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ChatbotUnknownQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'question',
        'normalized_question',
        'conversation_id',
        'detected_intent',
        'prediction_confidence',
        'suggested_domain',
        'occurrence_count',
        'last_seen_at',
        'admin_status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'occurrence_count' => 'integer',
            'prediction_confidence' => 'decimal:4',
            'last_seen_at' => 'datetime',
        ];
    }

    protected $table = 'chatbot_unknown_questions';

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatbotConversation::class, 'conversation_id');
    }
}
