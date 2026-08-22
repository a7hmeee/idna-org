<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Models;

use Illuminate\Database\Eloquent\Model;

final class ChatbotSearchAnalytics extends Model
{
    protected $fillable = [
        'conversation_id',
        'search_query',
        'normalized_query',
        'matched_service_id',
        'matched_service_name',
        'search_score',
        'clarification_required',
        'search_duration_ms',
        'no_result',
    ];

    protected function casts(): array
    {
        return [
            'search_score' => 'decimal:4',
            'clarification_required' => 'boolean',
            'no_result' => 'boolean',
            'search_duration_ms' => 'integer',
        ];
    }

    protected $table = 'chatbot_search_analytics';
}
