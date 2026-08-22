<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\EngineeringOfficeQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class EngineeringOfficesListHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private EngineeringOfficeQueryInterface $officeQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::EngineeringOfficesList;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $offices = $this->officeQuery->getPublishedEngineeringOffices(10);

        if (empty($offices)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد مكاتب هندسية مدرجة حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['المكاتب الهندسية:'];
        foreach ($offices as $i => $office) {
            $num = $i + 1;
            $engineer = $office->engineerName ? " - {$office->engineerName}" : '';
            $lines[] = "{$num}. {$office->officeName}{$engineer}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($o) => [
                'id' => $o->id,
                'name' => $o->officeName,
                'engineer' => $o->engineerName,
            ], $offices),
        );
    }
}
