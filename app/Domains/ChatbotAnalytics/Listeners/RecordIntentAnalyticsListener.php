<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Listeners;

use App\Domains\ChatbotAnalytics\Contracts\IntentAnalyticsRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Records intent analytics whenever a chat message is processed.
 * Hooked via ChatbotAnalyticsServiceProvider event map.
 */
final class RecordIntentAnalyticsListener implements ShouldQueue
{
    use InteractsWithQueue;

    public string $queue = 'analytics';

    public int $tries = 3;

    public function __construct(
        private readonly IntentAnalyticsRepositoryInterface $intentRepository,
    ) {}

    /**
     * Handles the generic "intent processed" payload.
     * This listener is called with an array payload dispatched from the action.
     */
    public function handleIntentRecorded(array $payload): void
    {
        $this->intentRepository->create($payload);
    }
}
