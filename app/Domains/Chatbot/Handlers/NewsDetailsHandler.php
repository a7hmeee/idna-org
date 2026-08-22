<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\NewsQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class NewsDetailsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private NewsQueryInterface $newsQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::NewsDetails;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $news = $this->newsQuery->searchPublishedNews($message->message, 5);

        if (empty($news)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت الخبر المطلوب.',
                type: 'empty_state',
            );
        }

        $details = $this->newsQuery->getPublishedNewsById($news[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت تفاصيل الخبر.',
                type: 'empty_state',
            );
        }

        $lines = ["{$details->title}"];

        if ($details->publishAt) {
            $lines[] = "تاريخ النشر: {$details->publishAt}";
        }
        if ($details->author) {
            $lines[] = "الكاتب: {$details->author}";
        }

        if ($details->summary) {
            $lines[] = '';
            $lines[] = $details->summary;
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
        );
    }
}
