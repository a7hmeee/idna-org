<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityPhoneHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityInfoQueryInterface $infoQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::MunicipalityPhone;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $contacts = $this->infoQuery->getOfficialContacts();

        $phones = array_values(array_filter($contacts, fn ($c) => in_array($c->type, ['phone', 'mobile', 'whatsapp'], true)));

        if (empty($phones)) {
            return new ChatResponseData(
                message: 'عذرًا، لا يتوفر رقم هاتف للبلدية حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['أرقام هاتف البلدية:'];
        foreach ($phones as $phone) {
            $label = $phone->label ? "{$phone->label}: " : '';
            $lines[] = "{$label}{$phone->value}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'contact',
        );
    }
}
