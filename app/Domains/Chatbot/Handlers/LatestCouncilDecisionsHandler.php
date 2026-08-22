<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\CouncilDecisionQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class LatestCouncilDecisionsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CouncilDecisionQueryInterface $decisionQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::LatestCouncilDecisions;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $decisions = $this->decisionQuery->getLatestPublishedDecisions(5);

        if (empty($decisions)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد قرارات منشورة حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['آخر قرارات المجلس:'];
        foreach ($decisions as $i => $decision) {
            $num = $i + 1;
            $number = $decision->decisionNumber ? " (رقم {$decision->decisionNumber})" : '';
            $date = $decision->decisionDate ? " - {$decision->decisionDate}" : '';
            $lines[] = "{$num}. {$decision->title}{$number}{$date}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($d) => [
                'id' => $d->id,
                'title' => $d->title,
                'decision_number' => $d->decisionNumber,
                'date' => $d->decisionDate,
            ], $decisions),
        );
    }
}
