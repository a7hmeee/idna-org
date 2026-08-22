<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ConversationFinishedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly int $conversationId,
        public readonly string $sessionId,
        public readonly string $endReason, // 'timeout' | 'user_ended' | 'workflow_completed'
        public readonly int $messageCount,
        public readonly int $durationMs,
    ) {}
}
