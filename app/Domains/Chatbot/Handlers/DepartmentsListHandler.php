<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\DepartmentQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class DepartmentsListHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private DepartmentQueryInterface $departmentQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::DepartmentsList;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $departments = $this->departmentQuery->getPublishedDepartments(10);

        if (empty($departments)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد أقسام مدرجة حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['الأقسام في البلدية:'];
        foreach ($departments as $i => $dept) {
            $num = $i + 1;
            $desc = $dept->shortDescription ? " - {$dept->shortDescription}" : '';
            $lines[] = "{$num}. {$dept->name}{$desc}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'description' => $d->shortDescription,
                'phone' => $d->phone,
            ], $departments),
        );
    }
}
