<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UnknownQuestionDetectedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly string $question,
        public readonly string $normalizedQuestion,
        public readonly ?int $conversationId,
        public readonly ?string $detectedIntent,
        public readonly ?float $predictionConfidence,
        public readonly ?string $suggestedDomain,
    ) {}
}
