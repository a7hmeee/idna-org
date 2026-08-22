<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class ServiceDurationHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ServiceDuration;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        if ($service === null) {
            return new ChatResponseData(
                message: 'ممكن توضح اسم الخدمة اللي بدك تعرف مدتها؟',
                type: 'clarification',
                needsClarification: true,
                clarificationType: 'service',
            );
        }

        $data = $this->serviceQuery->getPublishedDuration($service->id);
        $duration = $data->processingTime ?? null;

        if ($duration === null || $duration === '') {
            return new ChatResponseData(
                message: "عذرًا، مدة إنجاز الخدمة \"{$service->name}\" غير منشورة حاليًا.",
                type: 'text',
            );
        }

        return new ChatResponseData(
            message: "مدة إنجاز خدمة \"{$service->name}\": {$duration}",
            type: 'duration',
        );
    }
}
