<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Models;

use Illuminate\Database\Eloquent\Model;

final class ChatbotWorkflowAnalytics extends Model
{
    protected $fillable = [
        'conversation_id',
        'workflow_type',
        'workflow_draft_id',
        'started_at',
        'completed_at',
        'cancelled_at',
        'current_step',
        'total_steps',
        'completion_percentage',
        'was_completed',
        'was_cancelled',
        'validation_failures',
        'confirmed',
        'duration_ms',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'was_completed' => 'boolean',
            'was_cancelled' => 'boolean',
            'confirmed' => 'boolean',
            'completion_percentage' => 'decimal:2',
        ];
    }

    protected $table = 'chatbot_workflow_analytics';
}
