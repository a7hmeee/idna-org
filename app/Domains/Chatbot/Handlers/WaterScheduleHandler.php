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

final readonly class WaterScheduleHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private WaterScheduleQueryInterface $waterQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::WaterSchedule;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $areas = $this->waterQuery->getPublishedAreas();

        if (empty($areas)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد معلومات عن جدول المياه حاليًا.',
                type: 'empty_state',
            );
        }

        // Try to find area in message
        $query = $message->message;
        $matchedArea = null;
        foreach ($areas as $area) {
            if (str_contains(mb_strtolower($query), mb_strtolower($area->name))) {
                $matchedArea = $area;
                break;
            }
        }

        if ($matchedArea === null) {
            // Ask for area — options carry typed keys so clients can click
            // them and the pipeline can resolve by key instead of guessing.
            $lines = ['لأي منطقة تريد معرفة موعد المياه؟'];
            $items = [];
            $actions = [];

            foreach ($areas as $i => $area) {
                $num = $i + 1;
                $lines[] = "{$num}. {$area->name}";
                $items[] = [
                    'key' => "water-area:{$area->id}",
                    'label' => $area->name,
                    'id' => $area->id,
                    'name' => $area->name,
                    'entity_type' => 'water_area',
                    'entity_id' => $area->id,
                    'position' => $num,
                ];
                $actions[] = [
                    'label' => $area->name,
                    'value' => "water-area:{$area->id}",
                ];
            }

            return new ChatResponseData(
                message: implode("\n", $lines),
                type: 'clarification',
                needsClarification: true,
                clarificationType: 'water_area',
                items: $items,
                actions: $actions,
            );
        }

        $schedule = $this->waterQuery->getCurrentScheduleForArea($matchedArea->id);

        if ($schedule === null) {
            $schedule = $this->waterQuery->getLatestScheduleForArea($matchedArea->id);
        }

        if ($schedule === null) {
            return new ChatResponseData(
                message: "عذرًا، لا يوجد جدول مياه متاح حاليًا لمنطقة {$matchedArea->name}.",
                type: 'empty_state',
            );
        }

        $lines = ["جدول المياه لمنطقة {$matchedArea->name}:"];
        if ($schedule->scheduleDate !== now()->toDateString()) {
            $lines[] = "آخر جدول متاح (التاريخ: {$schedule->scheduleDate}):";
        } else {
            $lines[] = "التاريخ: {$schedule->scheduleDate}";
        }
        $timeRange = WaterTimeFormatter::formatRange($schedule->startTime, $schedule->endTime);
        if ($timeRange) {
            $lines[] = "الوقت: {$timeRange}";
        }
        if ($schedule->notes) {
            $lines[] = "ملاحظات: {$schedule->notes}";
        }

        $statusLabels = [
            'available' => 'متوفر',
            'low_pressure' => 'ضغط منخفض',
            'maintenance' => 'صيانة',
            'emergency' => 'طوارئ',
            'no_water' => 'لا يوجد مياه',
        ];
        $statusLabel = $statusLabels[$schedule->status] ?? $schedule->status;
        $lines[] = "الحالة: {$statusLabel}";

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'schedule',
            items: [['area' => $matchedArea->name, 'date' => $schedule->scheduleDate, 'time' => $timeRange]],
            actions: [['label' => 'موعد المياه القادم', 'value' => 'وبعدها']],
        );
    }
}
