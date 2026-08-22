<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\NewsQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class LatestNewsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private NewsQueryInterface $newsQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::LatestNews;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $news = $this->newsQuery->getLatestPublishedNews(5);

        if (empty($news)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد أخبار حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['آخر الأخبار:'];
        foreach ($news as $i => $item) {
            $num = $i + 1;
            $date = $item->publishAt ? " ({$item->publishAt})" : '';
            $summary = $item->summary ? " - {$item->summary}" : '';
            $lines[] = "{$num}. {$item->title}{$date}{$summary}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'summary' => $n->summary,
                'publish_at' => $n->publishAt,
            ], $news),
        );
    }
}
