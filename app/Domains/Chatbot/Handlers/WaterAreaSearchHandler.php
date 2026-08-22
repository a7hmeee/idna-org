<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class WaterAreaSearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private WaterScheduleQueryInterface $waterQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::WaterAreaSearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $areas = $this->waterQuery->getPublishedAreas();

        if (empty($areas)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد مناطق مياه متاحة حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['مناطق جدول المياه:'];
        foreach ($areas as $area) {
            $lines[] = "• {$area->name}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($a) => ['id' => $a->id, 'name' => $a->name], $areas),
        );
    }
}
