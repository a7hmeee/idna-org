<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Models;

use Illuminate\Database\Eloquent\Model;

final class ChatbotPerformanceLog extends Model
{
    protected $fillable = [
        'context',
        'handler_class',
        'action_label',
        'duration_ms',
        'memory_bytes',
        'db_query_count',
        'cache_hits',
        'cache_misses',
        'slow_flag',
    ];

    protected function casts(): array
    {
        return [
            'slow_flag' => 'boolean',
            'duration_ms' => 'integer',
            'memory_bytes' => 'integer',
            'db_query_count' => 'integer',
            'cache_hits' => 'integer',
            'cache_misses' => 'integer',
        ];
    }

    protected $table = 'chatbot_performance_logs';
}
