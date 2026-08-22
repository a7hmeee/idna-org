<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\DepartmentQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class DepartmentSearchHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private DepartmentQueryInterface $departmentQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::DepartmentSearch;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $query = $message->message;
        $departments = $this->departmentQuery->searchPublishedDepartments($query, 5);

        if (empty($departments)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت قسم بهالاسم. جرب اسم تاني.',
                type: 'empty_state',
            );
        }

        if (count($departments) === 1) {
            $dept = $departments[0];
            $lines = ["القسم: {$dept->name}"];
            if ($dept->phone) {
                $lines[] = "الهاتف: {$dept->phone}";
            }
            if ($dept->shortDescription) {
                $lines[] = $dept->shortDescription;
            }

            return new ChatResponseData(
                message: implode("\n", $lines),
                type: 'text',
            );
        }

        $lines = ['وجدت عدة أقسام مطابقة:'];
        foreach ($departments as $i => $dept) {
            $num = $i + 1;
            $lines[] = "{$num}. {$dept->name}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            needsClarification: true,
            clarificationType: 'department',
            items: array_map(fn ($d) => ['id' => $d->id, 'name' => $d->name], $departments),
        );
    }
}
