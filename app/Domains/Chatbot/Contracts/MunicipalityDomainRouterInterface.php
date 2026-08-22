<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\DTOs\MunicipalityDomainRouteData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

interface MunicipalityDomainRouterInterface
{
    public function route(
        ChatbotIntent $intent,
        string $message,
        ConversationStateData $context,
    ): MunicipalityDomainRouteData;
}
