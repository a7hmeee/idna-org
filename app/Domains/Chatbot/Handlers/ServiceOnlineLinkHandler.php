<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class ServiceOnlineLinkHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ServiceOnlineLink;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        if ($service === null) {
            return new ChatResponseData(
                message: 'ممكن توضح اسم الخدمة اللي بدك رابط التقديم الإلكتروني لها؟',
                type: 'clarification',
                needsClarification: true,
                clarificationType: 'service',
            );
        }

        $data = $this->serviceQuery->getPublishedOnlineLink($service->id);
        $url = $data->portalUrl ?? null;

        if ($url === null || $url === '') {
            return new ChatResponseData(
                message: "عذرًا، التقديم الإلكتروني لخدمة \"{$service->name}\" غير متاح حاليًا.",
                type: 'text',
                actions: [
                    ['label' => 'تفاصيل الخدمة', 'value' => "معلومات عن {$service->name}"],
                ],
            );
        }

        return new ChatResponseData(
            message: "رابط التقديم الإلكتروني لخدمة \"{$service->name}\":",
            type: 'actions',
            actions: [
                ['label' => 'تقديم الخدمة', 'url' => $url],
            ],
        );
    }
}
