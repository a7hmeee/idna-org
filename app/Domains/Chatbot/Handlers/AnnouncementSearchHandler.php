<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\AnnouncementQueryInterface;
use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class AnnouncementSearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private AnnouncementQueryInterface $announcementQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::AnnouncementSearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $announcements = $this->announcementQuery->searchPublishedAnnouncements($message->message, 5);

        if (empty($announcements)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت إعلان بهالموضوع.',
                type: 'empty_state',
            );
        }

        $lines = ['نتائج البحث عن إعلانات:'];
        foreach ($announcements as $i => $item) {
            $num = $i + 1;
            $lines[] = "{$num}. {$item->title}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($a) => ['id' => $a->id, 'title' => $a->title], $announcements),
        );
    }
}
