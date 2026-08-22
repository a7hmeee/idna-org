<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\CouncilDecisionQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class CouncilDecisionSearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CouncilDecisionQueryInterface $decisionQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::CouncilDecisionSearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $decisions = $this->decisionQuery->searchPublishedDecisions($message->message, 5);

        if (empty($decisions)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت قرار بهالموضوع.',
                type: 'empty_state',
            );
        }

        $lines = ['نتائج البحث عن قرارات:'];
        foreach ($decisions as $i => $decision) {
            $num = $i + 1;
            $lines[] = "{$num}. {$decision->title}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($d) => ['id' => $d->id, 'title' => $d->title], $decisions),
        );
    }
}
