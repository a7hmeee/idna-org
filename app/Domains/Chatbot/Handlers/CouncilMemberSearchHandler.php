<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\CouncilMemberQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class CouncilMemberSearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CouncilMemberQueryInterface $memberQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::CouncilMemberSearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $members = $this->memberQuery->searchPublishedCouncilMembers($message->message, 5);

        if (empty($members)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت عضو مجلس بهالاسم.',
                type: 'empty_state',
            );
        }

        $lines = ['نتائج البحث عن أعضاء المجلس:'];
        foreach ($members as $i => $member) {
            $num = $i + 1;
            $lines[] = "{$num}. {$member->fullName}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($m) => ['id' => $m->id, 'name' => $m->fullName], $members),
        );
    }
}
