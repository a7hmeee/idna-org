<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;

final readonly class ServiceRequirementsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
        private ArabicTextNormalizer $normalizer,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ServiceRequirements;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        if ($service === null) {
            return new ChatResponseData(
                message: 'ممكن توضح اسم الخدمة اللي بدك تعرف متطلباتها؟',
                type: 'clarification',
                needsClarification: true,
                clarificationType: 'service',
            );
        }

        $data = $this->serviceQuery->getPublishedRequirements($service->id);
        $requirements = $data->requirements ?? [];
        $documents = $data->documents ?? [];

        $allItems = [];

        if (count($requirements) > 0) {
            $allItems = array_merge($allItems, $requirements);
        }

        if (count($documents) > 0) {
            $allItems = array_merge($allItems, $documents);
        }

        $allItems = $this->deduplicateItems($allItems);

        if (count($allItems) === 0) {
            return new ChatResponseData(
                message: "عذرًا، متطلبات الخدمة \"{$service->name}\" غير منشورة حاليًا.",
                type: 'text',
                actions: [
                    ['label' => 'خطوات التقديم', 'key' => 'service-action:steps', 'payload' => ['service_id' => $service->id]],
                    ['label' => 'الرسوم', 'key' => 'service-action:fees', 'payload' => ['service_id' => $service->id]],
                ],
            );
        }

        return new ChatResponseData(
            message: "متطلبات خدمة \"{$service->name}\":",
            type: 'requirements',
            items: $allItems,
        );
    }

    private function deduplicateItems(array $items): array
    {
        $seen = [];
        $result = [];

        foreach ($items as $item) {
            $text = is_string($item)
                ? $item
                : ($item['label'] ?? $item['requirement'] ?? $item['name'] ?? '');

            $normalized = $this->normalizer->normalize((string) $text);

            if ($normalized === '' || isset($seen[$normalized])) {
                continue;
            }

            $seen[$normalized] = true;
            $result[] = $item;
        }

        return $result;
    }
}
