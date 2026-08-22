<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\CouncilDecisionQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class CouncilDecisionDetailsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CouncilDecisionQueryInterface $decisionQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::CouncilDecisionDetails;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $decisions = $this->decisionQuery->searchPublishedDecisions($message->message, 5);

        if (empty($decisions)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت القرار المطلوب.',
                type: 'empty_state',
            );
        }

        $details = $this->decisionQuery->getPublishedDecisionById($decisions[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت تفاصيل القرار.',
                type: 'empty_state',
            );
        }

        $lines = ["{$details->title}"];

        if ($details->decisionNumber) {
            $lines[] = "رقم القرار: {$details->decisionNumber}";
        }
        if ($details->decisionDate) {
            $lines[] = "تاريخ القرار: {$details->decisionDate}";
        }
        if ($details->type) {
            $lines[] = "النوع: {$details->type}";
        }
        if ($details->sessionNumber) {
            $lines[] = "رقم الجلسة: {$details->sessionNumber}";
        }
        if ($details->summary) {
            $lines[] = '';
            $lines[] = $details->summary;
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
        );
    }
}
