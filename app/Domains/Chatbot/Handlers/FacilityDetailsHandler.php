<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\FacilityQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class FacilityDetailsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private FacilityQueryInterface $facilityQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::FacilityDetails;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $facilities = $this->facilityQuery->searchPublishedFacilities($message->message, 5);

        if (empty($facilities)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت المرفق المطلوب.',
                type: 'empty_state',
            );
        }

        $details = $this->facilityQuery->getPublishedFacilityById($facilities[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت تفاصيل المرفق.',
                type: 'empty_state',
            );
        }

        $lines = ["المرفق: {$details->name}"];
        if ($details->categoryName) {
            $lines[] = "التصنيف: {$details->categoryName}";
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
