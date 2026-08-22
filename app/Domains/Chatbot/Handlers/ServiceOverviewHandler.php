<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class ServiceOverviewHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ServiceOverview;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        if ($service === null) {
            return new ChatResponseData(
                message: 'ممكن توضح اسم الخدمة اللي بدك تشوف معلومات عنها؟',
                type: 'clarification',
                needsClarification: true,
                clarificationType: 'service',
            );
        }

        $overview = $this->serviceQuery->getPublishedOverview($service->id);
        if ($overview === null) {
            return $this->unpublishedResponse($service);
        }

        $description = $overview->description;
        if ($description === null || $description === '') {
            return new ChatResponseData(
                message: "عذرًا، ما في وصف منشور للخدمة \"{$service->name}\" حاليًا.",
                type: 'text',
                actions: $this->defaultActions($service),
            );
        }

        return new ChatResponseData(
            message: $description,
            type: 'text',
            actions: $this->defaultActions($service),
        );
    }

    private function unpublishedResponse(ResolvedServiceData $service): ChatResponseData
    {
        return new ChatResponseData(
            message: "عذرًا، الخدمة \"{$service->name}\" مو متوفرة حاليًا.",
            type: 'text',
        );
    }

    private function defaultActions(ResolvedServiceData $service): array
    {
        $actions = [
            ['label' => 'خطوات التقديم', 'value' => "خطوات التقديم {$service->name}"],
            ['label' => 'المتطلبات', 'value' => "المتطلبات {$service->name}"],
            ['label' => 'الرسوم', 'value' => "الرسوم {$service->name}"],
        ];

        if ($service->portalUrl) {
            $actions[] = ['label' => 'رابط الخدمة', 'url' => $service->portalUrl];
        }

        return $actions;
    }
}
