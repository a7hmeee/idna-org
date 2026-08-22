<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class ServiceApplicationStepsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ServiceApplicationSteps;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        if ($service === null) {
            return new ChatResponseData(
                message: 'ممكن توضح اسم الخدمة اللي بدك تعرف خطوات التقديم عليها؟',
                type: 'clarification',
                needsClarification: true,
                clarificationType: 'service',
            );
        }

        $data = $this->serviceQuery->getPublishedApplicationGuide($service->id);
        $steps = $data->steps ?? [];

        if (count($steps) === 0) {
            return new ChatResponseData(
                message: "عذرًا، خطوات التقديم للخدمة \"{$service->name}\" غير منشورة حاليًا.",
                type: 'text',
                actions: [
                    ['label' => 'المتطلبات', 'value' => "المتطلبات {$service->name}"],
                    ['label' => 'الرسوم', 'value' => "الرسوم {$service->name}"],
                ],
            );
        }

        return new ChatResponseData(
            message: "خطوات التقديم لخدمة \"{$service->name}\":",
            type: 'steps',
            items: $steps,
        );
    }
}
