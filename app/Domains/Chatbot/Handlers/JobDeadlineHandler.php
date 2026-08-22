<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\Contracts\JobsQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class JobDeadlineHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private JobsQueryInterface $jobsQuery,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::JobDeadline;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $jobs = $this->jobsQuery->searchPublishedJobs($message->message, 5);

        if (empty($jobs)) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت معلومات عن الوظيفة المطلوبة.',
                type: 'empty_state',
            );
        }

        $details = $this->jobsQuery->getPublishedJobById($jobs[0]->id);

        if ($details === null) {
            return new ChatResponseData(
                message: 'عذرًا، ما لقيت معلومات عن الوظيفة.',
                type: 'empty_state',
            );
        }

        if ($details->closingAt === null) {
            return new ChatResponseData(
                message: "لا يوجد موعد إغلاق محدد للوظيفة: {$details->title}.",
                type: 'text',
            );
        }

        $lines = ["آخر موعد للتقديم على وظيفة {$details->title}:"];
        $lines[] = $details->closingAt;

        if ($details->applicationMethod) {
            $methodLabels = [
                'external_link' => 'رابط خارجي',
                'email' => 'بريد إلكتروني',
                'phone' => 'هاتف',
                'office' => 'تقديم في المكتب',
                'download_form' => 'تحميل استمارة',
            ];
            $method = $methodLabels[$details->applicationMethod] ?? $details->applicationMethod;
            $lines[] = "طريقة التقديم: {$method}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'date',
        );
    }
}
