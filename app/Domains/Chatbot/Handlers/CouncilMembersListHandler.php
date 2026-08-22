<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\CouncilMemberQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class CouncilMembersListHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CouncilMemberQueryInterface $memberQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::CouncilMembersList;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $members = $this->memberQuery->getPublishedCouncilMembers(10);

        if (empty($members)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد معلومات عن أعضاء المجلس حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['أعضاء المجلس البلدي:'];

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($m) => [
                'id' => $m->id,
                'name' => $m->fullName,
                'position' => $m->position,
            ], $members),
        );
    }
}
