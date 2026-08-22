<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class MunicipalityAboutHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private MunicipalityInfoQueryInterface $infoQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::MunicipalityAbout;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $profile = $this->infoQuery->getPublicProfile();
        $about = $this->infoQuery->getAboutSummary();

        if ($profile === null || $about === null) {
            return new ChatResponseData(
                message: 'عذرًا، لا تتوفر معلومات عن البلدية حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ["{$profile->nameAr}"];

        if ($profile->shortDescription) {
            $lines[] = '';
            $lines[] = $profile->shortDescription;
        }

        if ($profile->foundationDate) {
            $lines[] = '';
            $lines[] = "تاريخ التأسيس: {$profile->foundationDate}";
        }

        if ($profile->municipalityCode) {
            $lines[] = "رمز البلدية: {$profile->municipalityCode}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
        );
    }
}
