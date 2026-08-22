<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\EngineeringOfficeQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class EngineeringOfficeDetailsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private EngineeringOfficeQueryInterface $officeQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::EngineeringOfficeDetails;
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
                message: 'عذرًا، ما لقيت تفاصيل المكتب الهندسي.',
                type: 'empty_state',
            );
        }

        $lines = ["المكتب الهندسي: {$details->officeName}"];
        if ($details->engineerName) {
            $lines[] = "المهندس: {$details->engineerName}";
        }
        if ($details->licenseNumber) {
            $lines[] = "رقم الترخيص: {$details->licenseNumber}";
        }
        if ($details->specializations) {
            $lines[] = 'الاختصاصات: '.implode('، ', $details->specializations);
        }
        if ($details->phone) {
            $lines[] = "الهاتف: {$details->phone}";
        }
        if ($details->mobile) {
            $lines[] = "الجوال: {$details->mobile}";
        }
        if ($details->email) {
            $lines[] = "البريد الإلكتروني: {$details->email}";
        }
        if ($details->address) {
            $lines[] = "العنوان: {$details->address}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
        );
    }
}
