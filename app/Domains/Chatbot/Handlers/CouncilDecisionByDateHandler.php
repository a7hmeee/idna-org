<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\CouncilDecisionQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class CouncilDecisionByDateHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CouncilDecisionQueryInterface $decisionQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::CouncilDecisionByDate;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $query = $message->message;
        $date = $this->extractDate($query);

        if ($date === null) {
            return new ChatResponseData(
                message: 'ممكن توضيح التاريخ المطلوب؟ مثال: "قرارات 2026-07-01"',
                type: 'clarification',
                needsClarification: true,
            );
        }

        $decisions = $this->decisionQuery->searchPublishedDecisionsByDate($date, 5);

        if (empty($decisions)) {
            return new ChatResponseData(
                message: "عذرًا، لا توجد قرارات في التاريخ: {$date}.",
                type: 'empty_state',
            );
        }

        $lines = ["قرارات المجلس بتاريخ {$date}:"];
        foreach ($decisions as $i => $decision) {
            $num = $i + 1;
            $lines[] = "{$num}. {$decision->title}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($d) => ['id' => $d->id, 'title' => $d->title, 'date' => $d->decisionDate], $decisions),
        );
    }

    private function extractDate(string $message): ?string
    {
        // Try to find YYYY-MM-DD pattern
        if (preg_match('/\b(\d{4}-\d{2}-\d{2})\b/', $message, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
