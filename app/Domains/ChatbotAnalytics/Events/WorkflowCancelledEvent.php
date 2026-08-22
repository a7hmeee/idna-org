<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class WorkflowCancelledEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly int $conversationId,
        public readonly string $workflowType,
        public readonly ?int $draftId,
        public readonly int $currentStep,
        public readonly int $totalSteps,
        public readonly string $cancellationReason,
    ) {}
}
