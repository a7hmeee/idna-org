<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Listeners;

use App\Domains\Chatbot\Models\ChatbotFeedback;
use App\Domains\ChatbotAnalytics\Events\FeedbackSubmittedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Persists citizen feedback to chatbot_feedback table.
 */
final class RecordFeedbackListener implements ShouldQueue
{
    use InteractsWithQueue;

    public string $queue = 'analytics';

    public int $tries = 3;

    public function handle(FeedbackSubmittedEvent $event): void
    {
        ChatbotFeedback::create([
            'message_id' => $event->messageId,
            'type' => $event->rating >= 4 ? 'positive' : ($event->rating <= 2 ? 'negative' : 'neutral'),
            'comment' => $event->comment,
        ]);
    }
}
