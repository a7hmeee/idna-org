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

final readonly class ServiceSearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityServiceQueryInterface $serviceQuery,
        private ArabicTextNormalizer $normalizer,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ServiceSearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        if ($service !== null) {
            return new ChatResponseData(
                message: "هذه هي الخدمة اللي بتدور عليها: {$service->name}",
                type: 'service_cards',
                items: [$service->toArray()],
                actions: [
                    ['label' => 'نظرة عامة', 'value' => "معلومات عن {$service->name}"],
                    ['label' => 'خطوات التقديم', 'value' => "خطوات التقديم {$service->name}"],
                    ['label' => 'المتطلبات', 'value' => "المتطلبات {$service->name}"],
                    ['label' => 'الرسوم', 'value' => "الرسوم {$service->name}"],
                    ['label' => 'المدة', 'value' => "المده {$service->name}"],
                ],
            );
        }

        $searchPhrase = $this->extractSearchPhrase($message->message);
        $results = $this->serviceQuery->searchPublished($searchPhrase, 5);

        if (count($results) > 0) {
            return new ChatResponseData(
                message: 'لقيت هالخدمات:',
                type: 'service_cards',
                items: array_map(fn ($s) => $s->toArray(), $results),
            );
        }

        return new ChatResponseData(
            message: 'للأسف ما لقيت خدمة بهالاسم. ممكن تكتب اسم الخدمة الرسمي أو تقريبًا؟',
            type: 'clarification',
            needsClarification: true,
            clarificationType: 'service',
        );
    }

    private function extractSearchPhrase(string $originalMessage): string
    {
        $stopWords = [
            'بدي', 'ابحث عن', 'دورلي على', 'شو في', 'خدمات',
            'خدمه', 'خدمة', 'البندية', 'البلدية',
        ];

        $normalized = $this->normalizer->normalize($originalMessage);

        foreach ($stopWords as $word) {
            $normalizedWord = $this->normalizer->normalize($word);
            $normalized = str_replace($normalizedWord, '', $normalized);
        }

        return trim($normalized) ?: $originalMessage;
    }
}
