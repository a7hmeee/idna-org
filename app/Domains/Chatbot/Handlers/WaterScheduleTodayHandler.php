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

final readonly class WaterScheduleTodayHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private WaterScheduleQueryInterface $waterQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::WaterScheduleToday;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $schedules = $this->waterQuery->getTodaySchedules();

        if (empty($schedules)) {
            return new ChatResponseData(
                message: 'لا يوجد جدول مياه مضبوط لليوم. إليك آخر الجداول المتاحة:',
                type: 'schedule',
                items: $this->getLatestSchedulesAsItems(),
            );
        }

        $lines = ['جدول المياه لليوم:'];
        foreach ($schedules as $s) {
            $time = WaterTimeFormatter::formatRange($s->startTime, $s->endTime) ?? 'غير محدد';
            $lines[] = "• {$s->areaName}: {$time}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'schedule',
            items: array_map(fn ($s) => [
                'area' => $s->areaName,
                'date' => $s->scheduleDate,
                'time' => WaterTimeFormatter::formatRange($s->startTime, $s->endTime),
            ], $schedules),
        );
    }

    private function getLatestSchedulesAsItems(): array
    {
        $areas = $this->waterQuery->getPublishedAreas();
        $items = [];

        foreach ($areas as $area) {
            $schedule = $this->waterQuery->getLatestScheduleForArea($area->id);
            if ($schedule !== null) {
                $items[] = [
                    'area' => $schedule->areaName,
                    'date' => $schedule->scheduleDate,
                    'time' => WaterTimeFormatter::formatRange($schedule->startTime, $schedule->endTime),
                ];
            }
        }

        if (empty($items)) {
            return [['message' => 'لا توجد جداول مياه متاحة حاليًا.']];
        }

        return $items;
    }
}
