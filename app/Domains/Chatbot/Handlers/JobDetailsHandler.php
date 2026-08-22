<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\JobsQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class JobDetailsHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private JobsQueryInterface $jobsQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::JobDetails;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $jobs = $this->jobsQuery->searchPublishedJobs($message->message, 5);

        if (empty($jobs)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت الوظيفة المطلوبة.',
                type: 'empty_state',
            );
        }

        $details = $this->jobsQuery->getPublishedJobById($jobs[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت تفاصيل الوظيفة.',
                type: 'empty_state',
            );
        }

        $lines = ["الوظيفة: {$details->title}"];

        if ($details->jobNumber) {
            $lines[] = "رقم الوظيفة: {$details->jobNumber}";
        }
        if ($details->departmentName) {
            $lines[] = "القسم: {$details->departmentName}";
        }
        if ($details->employmentType) {
            $lines[] = "نوع العمل: {$details->employmentType}";
        }
        if ($details->location) {
            $lines[] = "الموقع: {$details->location}";
        }
        if ($details->vacancies) {
            $lines[] = "عدد الشواغر: {$details->vacancies}";
        }
        if ($details->summary) {
            $lines[] = '';
            $lines[] = $details->summary;
        }

        $lines[] = '';
        if ($details->closingAt) {
            $lines[] = "آخر موعد للتقديم: {$details->closingAt}";
        }
        if ($details->salary) {
            $lines[] = "الراتب: {$details->salary}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'text',
        );
    }
}
