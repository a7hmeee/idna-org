<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityWorkingHoursHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityInfoQueryInterface $infoQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::MunicipalityWorkingHours;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $hours = $this->infoQuery->getWorkingHours();

        if (empty($hours)) {
            return new ChatResponseData(
                message: 'عذرًا، لا تتوفر معلومات عن ساعات العمل حاليًا.',
                type: 'empty_state',
            );
        }

        $dayNames = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد',
            'monday' => 'الإثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
        ];

        $lines = ['ساعات العمل:'];
        foreach ($hours as $h) {
            $dayName = $dayNames[mb_strtolower($h->day)] ?? $h->day;
            if ($h->isClosed) {
                $lines[] = "{$dayName}: مغلق";
            } else {
                $time = $h->openTime && $h->closeTime ? "{$h->openTime} - {$h->closeTime}" : ($h->notes ?? 'غير محدد');
                $lines[] = "{$dayName}: {$time}";
            }
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
        );
    }
}
