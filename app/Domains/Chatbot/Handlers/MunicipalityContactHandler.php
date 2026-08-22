<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Services\PublicChatbotDataQualityGuard;

final readonly class MunicipalityContactHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityInfoQueryInterface $infoQuery,
        private PublicChatbotDataQualityGuard $dataGuard,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::MunicipalityContact;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $contacts = $this->infoQuery->getOfficialContacts();

        $filtered = array_values(array_filter($contacts, function ($c) {
            $value = (string) ($c->value ?? '');

            return ! $this->dataGuard->isDemoValue($value);
        }));

        if (empty($filtered)) {
            return new ChatResponseData(
                message: 'معلومات التواصل الرسمية غير مكتملة حاليًا. يمكنك زيارة صفحة "تواصل معنا" للمعلومات المتاحة.',
                type: 'text',
            );
        }

        $lines = ['معلومات الاتصال بالبلدية:'];

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'contact',
            items: array_map(fn ($c) => [
                'type' => $c->type,
                'label' => $c->label ?? $c->type,
                'value' => $c->value,
                'url' => $c->url,
            ], $filtered),
        );
    }
}
