<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\FacilityQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class FacilitiesListHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private FacilityQueryInterface $facilityQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::FacilitiesList;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $facilities = $this->facilityQuery->getPublishedFacilities(10);

        if (empty($facilities)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد المرافق العامة مدرجة حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['المرافق العامة المتاحة:'];

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($f) => ['id' => $f->id, 'name' => $f->name], $facilities),
        );
    }
}
