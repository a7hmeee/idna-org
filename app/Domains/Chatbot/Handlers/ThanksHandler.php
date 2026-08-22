<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class ThanksHandler implements ChatResponseHandlerInterface
{
    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::Thanks;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        return new ChatResponseData(
            message: 'العفو، سعيد إني قدرت أساعدك. إذا في شي تاني بدك إياه أنا هون.',
            type: 'text',
        );
    }
}
