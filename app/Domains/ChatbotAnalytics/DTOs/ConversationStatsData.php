<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\DTOs;

final readonly class ConversationStatsData
{
    public function __construct(
        public int $totalConversations,
        public int $totalMessages,
        public int $activeConversations,
        public float $avgMessagesPerConversation,
        public float $avgResponseTimeMs,
        public int $successfulConversations,
        public int $failedConversations,
        public int $escalatedConversations,
        public float $feedbackPositiveRate,
        public int $totalFeedback,
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
