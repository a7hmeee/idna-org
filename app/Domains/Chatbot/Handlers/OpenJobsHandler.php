<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\JobsQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class OpenJobsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private JobsQueryInterface $jobsQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::JobsOpen;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $jobs = $this->jobsQuery->getOpenJobs(5);

        if (empty($jobs)) {
            return new ChatResponseData(
                message: 'لا توجد وظائف مفتوحة حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['هذه هي الوظائف المفتوحة حالياً:'];

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($j) => [
                'id' => $j->id,
                'title' => $j->title,
                'department' => $j->departmentName,
                'closing_at' => $j->closingAt,
                'type' => $j->employmentType,
            ], $jobs),
        );
    }
}
