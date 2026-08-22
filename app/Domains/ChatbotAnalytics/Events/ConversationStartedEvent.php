<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ConversationStartedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly int $conversationId,
        public readonly string $sessionId,
        public readonly ?int $userId,
    ) {}
}
