<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\FacilityQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class FacilityLocationHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private FacilityQueryInterface $facilityQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::FacilityLocation;
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
                message: 'عذرًا، ما لقيت موقع المرفق.',
                type: 'empty_state',
            );
        }

        if ($details->address === null) {
            return new ChatResponseData(
                message: "عذرًا، لا يتوفر موقع مسجل لـ {$details->name}.",
                type: 'empty_state',
            );
        }

        $lines = ["موقع {$details->name}:"];
        $lines[] = $details->address;

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'location',
            items: [['name' => $details->name, 'address' => $details->address]],
        );
    }
}
