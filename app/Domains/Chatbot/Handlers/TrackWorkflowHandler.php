<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\CitizenWorkflows\Contracts\WorkflowTrackingResolverInterface;

final readonly class TrackWorkflowHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private WorkflowTrackingResolverInterface $trackingResolver,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::TrackWorkflow;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $trackingNumber = $this->extractTrackingNumber($message->message);

        if ($trackingNumber === null) {
            return new ChatResponseData(
                message: 'الرجاء إدخال رقم المتابعة لتتبع حالة الطلب.',
                type: 'workflow_tracking',
                workflow: [
                    'type' => 'tracking',
                    'draft_id' => null,
                    'current_step' => 'tracking_number',
                    'total_steps' => 1,
                    'completed_steps' => 0,
                    'step_number' => 1,
                    'progress_percent' => 0.0,
                ],
            );
        }

        $result = $this->trackingResolver->resolveByTrackingNumber($trackingNumber);

        if ($result === null) {
            return new ChatResponseData(
                message: "لم يتم العثور على طلب برقم المتابعة: {$trackingNumber}\nالرجاء التأكد من رقم المتابعة.",
                type: 'workflow_not_found',
            );
        }

        $lines = [
            "حالة الطلب (رقم المتابعة: {$result->trackingNumber})",
            "النوع: {$this->typeLabel($result->type)}",
            "الحالة: {$result->statusLabel}",
        ];

        if ($result->submittedDate) {
            $lines[] = "تاريخ التقديم: {$result->submittedDate}";
        }

        if ($result->subject) {
            $lines[] = "الموضوع: {$result->subject}";
        }

        if ($result->department) {
            $lines[] = "القسم: {$result->department}";
        }

        if ($result->lastPublicUpdate) {
            $lines[] = "آخر تحديث: {$result->lastPublicUpdate}";
        }

        return new ChatResponseData(
            message: implode("\n", $lines),
            type: 'workflow_tracking',
            items: [
                'tracking_number' => $result->trackingNumber,
                'type' => $result->type,
                'status' => $result->status,
                'status_label' => $result->statusLabel,
                'submitted_date' => $result->submittedDate,
                'subject' => $result->subject,
            ],
        );
    }

    private function extractTrackingNumber(string $message): ?string
    {
        $pattern = '/[A-Za-z]{3,4}-[A-Za-z0-9]{6,12}/';
        if (preg_match($pattern, $message, $matches)) {
            return $matches[0];
        }

        $digits = preg_replace('/\D/', '', $message);
        if (strlen($digits) >= 6) {
            return $digits;
        }

        return null;
    }

    private function typeLabel(string $type): string
    {
        return match ($type) {
            'complaint' => 'شكوى',
            'contact_request' => 'طلب اتصال',
            default => $type,
        };
    }
}
