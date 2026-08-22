<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\DepartmentQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class DepartmentContactHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private DepartmentQueryInterface $departmentQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::DepartmentContact;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $query = $message->message;
        $departments = $this->departmentQuery->searchPublishedDepartments($query, 5);

        if (empty($departments)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت القسم المطلوب.',
                type: 'empty_state',
            );
        }

        $details = $this->departmentQuery->getPublishedDepartmentById($departments[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت معلومات الاتصال للقسم المطلوب.',
                type: 'empty_state',
            );
        }

        $lines = ["معلومات الاتصال: {$details->name}"];

        if ($details->phone) {
            $lines[] = "هاتف: {$details->phone}";
        }
        if ($details->extension) {
            $lines[] = "تحويلة: {$details->extension}";
        }
        if ($details->mobile) {
            $lines[] = "جوال: {$details->mobile}";
        }
        if ($details->email) {
            $lines[] = "بريد إلكتروني: {$details->email}";
        }
        if ($details->officeLocation) {
            $lines[] = "الموقع: {$details->officeLocation}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'contact',
        );
    }
}
