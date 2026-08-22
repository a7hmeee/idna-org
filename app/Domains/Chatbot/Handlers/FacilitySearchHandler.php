<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\FacilityQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class FacilitySearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private FacilityQueryInterface $facilityQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::FacilitySearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $facilities = $this->facilityQuery->searchPublishedFacilities($message->message, 5);

        if (empty($facilities)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت مرفق بهالاسم.',
                type: 'empty_state',
            );
        }

        $lines = ['نتائج البحث عن مرافق:'];
        foreach ($facilities as $i => $facility) {
            $num = $i + 1;
            $lines[] = "{$num}. {$facility->name}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($f) => ['id' => $f->id, 'name' => $f->name], $facilities),
        );
    }
}
