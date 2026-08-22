<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\DepartmentQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class DepartmentDetailsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private DepartmentQueryInterface $departmentQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::DepartmentDetails;
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
                message: 'عذرًا، ما لقيت القسم المطلوب.',
                type: 'empty_state',
            );
        }

        $lines = ["القسم: {$details->name}"];
        if ($details->managerName) {
            $lines[] = "مدير القسم: {$details->managerName}";
        }
        if ($details->phone) {
            $lines[] = "الهاتف: {$details->phone}";
        }
        if ($details->email) {
            $lines[] = "البريد الإلكتروني: {$details->email}";
        }
        if ($details->officeLocation) {
            $lines[] = "الموقع: {$details->officeLocation}";
        }
        if ($details->shortDescription) {
            $lines[] = '';
            $lines[] = $details->shortDescription;
        }
        if ($details->workingHours) {
            $lines[] = '';
            $lines[] = "ساعات العمل: {$details->workingHours}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
        );
    }
}
