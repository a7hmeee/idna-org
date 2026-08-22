<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\JobsQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class LatestJobsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private JobsQueryInterface $jobsQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::LatestJobs;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $jobs = $this->jobsQuery->getLatestPublishedJobs(5);

        if (empty($jobs)) {
            return new ChatResponseData(
                message: 'عذرًا، لا توجد وظائف منشورة حاليًا.',
                type: 'empty_state',
            );
        }

        $lines = ['آخر الوظائف:'];
        foreach ($jobs as $i => $job) {
            $num = $i + 1;
            $lines[] = "{$num}. {$job->title}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'list',
            items: array_map(fn ($j) => ['id' => $j->id, 'title' => $j->title], $jobs),
        );
    }
}
