<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\NewsQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class NewsSearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private NewsQueryInterface $newsQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::NewsSearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $news = $this->newsQuery->searchPublishedNews($message->message, 5);

        if (empty($news)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت خبر بهالموضوع.',
                type: 'empty_state',
            );
        }

        $lines = ['نتائج البحث:'];
        foreach ($news as $i => $item) {
            $num = $i + 1;
            $lines[] = "{$num}. {$item->title}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($n) => ['id' => $n->id, 'title' => $n->title], $news),
        );
    }
}
