<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Services\WaterTimeFormatter;

final readonly class WaterScheduleNextHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private WaterScheduleQueryInterface $waterQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::WaterScheduleNext;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        // Use context to find current area
        $query = $message->message;
        $areas = $this->waterQuery->getPublishedAreas();

        $matchedArea = null;
        foreach ($areas as $area) {
            if (str_contains(mb_strtolower($query), mb_strtolower($area->name))) {
                $matchedArea = $area;
                break;
            }
        }

        if ($matchedArea === null && count($areas) > 0) {
            $matchedArea = $areas[0];
        }

        if ($matchedArea === null) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد مناطق مياه متاحة.',
                type: 'empty_state',
            );
        }

        $schedule = $this->waterQuery->getNextScheduleForArea($matchedArea->id);

        if ($schedule === null) {
            $schedule = $this->waterQuery->getLatestScheduleForArea($matchedArea->id);
        }

        if ($schedule === null) {
            return new ChatResponseData(
                message: "عذرًا، لا يوجد موعد قادم متاح لمنطقة {$matchedArea->name} حاليًا.",
                type: 'empty_state',
            );
        }

        $lines = ["موعد المياه القادم لمنطقة {$matchedArea->name}:"];
        $lines[] = "التاريخ: {$schedule->scheduleDate}";
        $timeRange = WaterTimeFormatter::formatRange($schedule->startTime, $schedule->endTime);
        if ($timeRange) {
            $lines[] = "الوقت: {$timeRange}";
        }
        if ($schedule->notes) {
            $lines[] = "ملاحظات: {$schedule->notes}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'schedule',
        );
    }
}
