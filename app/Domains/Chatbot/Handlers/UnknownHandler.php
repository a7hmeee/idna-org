<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class UnknownHandler implements ChatResponseHandlerInterface
{
    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::Unknown;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        return new ChatResponseData(
            message: 'ما قدرت أحدد طلبك بالضبط. اختر من الخيارات أدناه:',
            type: 'clarification',
            needsClarification: true,
            clarificationType: 'municipality_main_menu',
            actions: GreetingHandler::MAIN_MENU_ACTIONS,
        );
    }
}
