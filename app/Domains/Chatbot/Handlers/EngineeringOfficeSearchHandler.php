<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\EngineeringOfficeQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class EngineeringOfficeSearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private EngineeringOfficeQueryInterface $officeQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::EngineeringOfficeSearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $offices = $this->officeQuery->searchPublishedEngineeringOffices($message->message, 5);

        if (empty($offices)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت مكتب هندسي بهالاسم.',
                type: 'empty_state',
            );
        }

        $lines = ['نتائج البحث عن مكاتب هندسية:'];
        foreach ($offices as $i => $office) {
            $num = $i + 1;
            $lines[] = "{$num}. {$office->officeName}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($o) => ['id' => $o->id, 'name' => $o->officeName], $offices),
        );
    }
}
