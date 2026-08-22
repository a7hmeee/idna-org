<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Listeners;

use App\Domains\ChatbotAnalytics\Contracts\UnknownQuestionRepositoryInterface;
use App\Domains\ChatbotAnalytics\Events\UnknownQuestionDetectedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Persists unknown questions to the chatbot_unknown_questions table.
 * Deduplication is handled via createOrIncrement in the repository.
 */
final class RecordUnknownQuestionListener implements ShouldQueue
{
    use InteractsWithQueue;

    public string $queue = 'analytics';

    public int $tries = 3;

    public function __construct(
        private readonly UnknownQuestionRepositoryInterface $unknownRepository,
    ) {}

    public function handle(UnknownQuestionDetectedEvent $event): void
    {
        $this->unknownRepository->createOrIncrement(
            question: $event->question,
            normalizedQuestion: $event->normalizedQuestion,
            extra: array_filter([
                'conversation_id' => $event->conversationId,
                'detected_intent' => $event->detectedIntent,
                'prediction_confidence' => $event->predictionConfidence,
                'suggested_domain' => $event->suggestedDomain,
            ], fn ($v) => $v !== null),
        );
    }
}
