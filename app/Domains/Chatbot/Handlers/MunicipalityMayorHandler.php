<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\CouncilMemberQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityMayorHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CouncilMemberQueryInterface $memberQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::MunicipalityMayor;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $mayor = $this->memberQuery->getPublishedMayor();

        if ($mayor === null || ! $mayor->isPublic) {
            return new ChatResponseData(
                message: 'معلومات رئيس البلدية غير منشورة حالياً.',
                type: 'empty_state',
            );
        }

        $lines = ["رئيس البلدية: {$mayor->fullName}"];
        if (! empty($mayor->position)) {
            $lines[] = "المنصب: {$mayor->position}";
        }
        if (! empty($mayor->qualification)) {
            $lines[] = "المؤهل: {$mayor->qualification}";
        }
        if (! empty($mayor->bio)) {
            $lines[] = "نبذة: {$mayor->bio}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
            actions: [
                ['label' => 'أعضاء المجلس البلدي', 'value' => 'أعضاء المجلس البلدي'],
                ['label' => 'قرارات المجلس', 'value' => 'قرارات المجلس'],
            ],
        );
    }
}
