<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\EngineeringOfficeQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class EngineeringOfficeContactHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private EngineeringOfficeQueryInterface $officeQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::EngineeringOfficeContact;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $offices = $this->officeQuery->searchPublishedEngineeringOffices($message->message, 5);

        if (empty($offices)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت المكتب الهندسي المطلوب.',
                type: 'empty_state',
            );
        }

        $details = $this->officeQuery->getPublishedEngineeringOfficeById($offices[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت معلومات الاتصال للمكتب الهندسي.',
                type: 'empty_state',
            );
        }

        $lines = ["معلومات الاتصال: {$details->officeName}"];
        if ($details->phone) {
            $lines[] = "هاتف: {$details->phone}";
        }
        if ($details->mobile) {
            $lines[] = "جوال: {$details->mobile}";
        }
        if ($details->email) {
            $lines[] = "بريد إلكتروني: {$details->email}";
        }
        if ($details->address) {
            $lines[] = "العنوان: {$details->address}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'contact',
        );
    }
}
