<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\CouncilMemberQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class CouncilMemberDetailsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CouncilMemberQueryInterface $memberQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::CouncilMemberDetails;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $members = $this->memberQuery->searchPublishedCouncilMembers($message->message, 5);

        if (empty($members)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت عضو المجلس المطلوب.',
                type: 'empty_state',
            );
        }

        $details = $this->memberQuery->getPublishedCouncilMemberById($members[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت تفاصيل عضو المجلس.',
                type: 'empty_state',
            );
        }

        $lines = ["عضو المجلس: {$details->fullName}"];
        if ($details->position) {
            $lines[] = "المنصب: {$details->position}";
        }
        if ($details->qualification) {
            $lines[] = "المؤهل: {$details->qualification}";
        }
        if ($details->committee) {
            $lines[] = "اللجنة: {$details->committee}";
        }
        if ($details->bio) {
            $lines[] = '';
            $lines[] = $details->bio;
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
        );
    }
}
