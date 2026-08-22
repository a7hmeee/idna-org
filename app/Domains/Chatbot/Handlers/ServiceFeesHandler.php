<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class ServiceFeesHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ServiceFees;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        if ($service === null) {
            return new ChatResponseData(
                message: 'ممكن توضح اسم الخدمة اللي بدك تعرف رسومها؟',
                type: 'clarification',
                needsClarification: true,
                clarificationType: 'service',
            );
        }

        $data = $this->serviceQuery->getPublishedFees($service->id);
        $fees = $data->fees ?? null;

        if ($fees === null) {
            return new ChatResponseData(
                message: "عذرًا، رسوم الخدمة \"{$service->name}\" غير منشورة حاليًا.",
                type: 'text',
                actions: [
                    ['label' => 'صفحة الخدمة', 'value' => "معلومات عن {$service->name}"],
                ],
            );
        }

        if (is_array($fees)) {
            return new ChatResponseData(
                message: "رسوم خدمة \"{$service->name}\":",
                type: 'fee',
                items: $fees,
            );
        }

        return new ChatResponseData(
            message: (string) $fees,
            type: 'fee',
        );
    }
}
