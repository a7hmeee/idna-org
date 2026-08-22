<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityEmailHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityInfoQueryInterface $infoQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::MunicipalityEmail;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $contacts = $this->infoQuery->getOfficialContacts();

        $emails = array_values(array_filter($contacts, fn ($c) => $c->type === 'email'));

        if (empty($emails)) {
            return new ChatResponseData(
                message: 'عذرًا، لا يتوفر بريد إلكتروني للبلدية حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['البريد الإلكتروني للبلدية:'];
        foreach ($emails as $email) {
            $label = $email->label ? "{$email->label}: " : '';
            $lines[] = "{$label}{$email->value}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'contact',
        );
    }
}
