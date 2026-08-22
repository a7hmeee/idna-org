<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityAddressHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityInfoQueryInterface $infoQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::MunicipalityAddress;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $address = $this->infoQuery->getAddress();

        if ($address === null) {
            return new ChatResponseData(
                message: 'عذرًا، لا يتوفر عنوان للبلدية حاليًا.',
                type: 'empty_state',
            );
        }

        $response = "عنوان البلدية: {$address->value}";
        $items = [['type' => 'address', 'label' => 'العنوان', 'value' => $address->value]];

        if ($address->url !== null) {
            $response .= "\n\nرابط الموقع: {$address->url}";
        }

        return new ChatResponseData(
            message: $response,
            type: 'location',
            items: $items,
            actions: $address->url !== null
                ? [['label' => 'فتح الموقع على الخريطة', 'value' => $address->url, 'url' => $address->url]]
                : [],
        );
    }
}
