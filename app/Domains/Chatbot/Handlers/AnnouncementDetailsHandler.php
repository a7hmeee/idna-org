<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\AnnouncementQueryInterface;
use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class AnnouncementDetailsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private AnnouncementQueryInterface $announcementQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::AnnouncementDetails;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $announcements = $this->announcementQuery->searchPublishedAnnouncements($message->message, 5);

        if (empty($announcements)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت الإعلان المطلوب.',
                type: 'empty_state',
            );
        }

        $details = $this->announcementQuery->getPublishedAnnouncementById($announcements[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت تفاصيل الإعلان.',
                type: 'empty_state',
            );
        }

        $lines = ["{$details->title}"];

        if ($details->publishedAt) {
            $lines[] = "تاريخ النشر: {$details->publishedAt}";
        }

        if ($details->shortDescription) {
            $lines[] = '';
            $lines[] = $details->shortDescription;
        }

        if ($details->externalUrl) {
            $lines[] = '';
            $lines[] = "للمزيد: {$details->externalUrl}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
            actions: $details->externalUrl !== null
                ? [['label' => 'المزيد من التفاصيل', 'value' => $details->externalUrl, 'url' => $details->externalUrl]]
                : [],
        );
    }
}
