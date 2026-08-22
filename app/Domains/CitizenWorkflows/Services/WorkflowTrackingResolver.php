<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Services;

use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowTrackingResolverInterface;
use App\Domains\CitizenWorkflows\DTOs\WorkflowTrackingData;
use App\Domains\CitizenWorkflows\DTOs\WorkflowTrackingResultData;
use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use App\Domains\ContactRequests\Enums\ContactRequestStatus;

class WorkflowTrackingResolver implements WorkflowTrackingResolverInterface
{
    public function __construct(
        private WorkflowDraftRepositoryInterface $draftRepository,
        private ComplaintRepositoryInterface $complaintRepository,
        private ContactRequestRepositoryInterface $contactRequestRepository,
    ) {}

    public function resolveByTrackingNumber(string $trackingNumber): ?WorkflowTrackingResultData
    {
        // 1. Check Complaints
        $complaint = $this->complaintRepository->findByTrackingNumber($trackingNumber);
        if ($complaint !== null) {
            $submittedAt = $complaint->submitted_at ?? $complaint->created_at;

            return new WorkflowTrackingResultData(
                trackingNumber: $complaint->tracking_number,
                type: 'complaint',
                status: $complaint->status instanceof ComplaintStatus
                    ? $complaint->status->value
                    : (string) $complaint->status,
                statusLabel: $complaint->status instanceof ComplaintStatus
                    ? $complaint->status->label()
                    : (string) $complaint->status,
                submittedDate: $submittedAt?->format('Y-m-d'),
                lastPublicUpdate: $complaint->updated_at?->format('Y-m-d'),
                department: $complaint->department?->name,
                subject: $complaint->subject,
            );
        }

        // 2. Check Contact Requests
        $contactRequest = $this->contactRequestRepository->findByTrackingNumber($trackingNumber);
        if ($contactRequest !== null) {
            $statusValue = $contactRequest->status instanceof ContactRequestStatus
                ? $contactRequest->status->value
                : (string) $contactRequest->status;
            $submittedAt = $contactRequest->submitted_at ?? $contactRequest->created_at;

            return new WorkflowTrackingResultData(
                trackingNumber: $contactRequest->tracking_number,
                type: 'contact_request',
                status: $statusValue,
                statusLabel: $this->contactRequestStatusLabel($statusValue),
                submittedDate: $submittedAt?->format('Y-m-d'),
                lastPublicUpdate: $contactRequest->updated_at?->format('Y-m-d'),
                department: $contactRequest->department,
                subject: $contactRequest->message,
            );
        }

        // 3. Check Workflow Drafts
        $draft = $this->draftRepository->findByTracking($trackingNumber);
        if ($draft !== null) {
            $answers = is_array($draft->answers) ? $draft->answers : [];

            return new WorkflowTrackingResultData(
                trackingNumber: $draft->tracking_number,
                type: $draft->workflow_type,
                status: $draft->status,
                statusLabel: $this->draftStatusLabel($draft->status),
                submittedDate: $draft->created_at?->format('Y-m-d'),
                lastPublicUpdate: $draft->updated_at?->format('Y-m-d'),
                subject: is_string($answers['subject'] ?? null)
                    ? $answers['subject']
                    : (is_string($answers['description'] ?? null) ? $answers['description'] : null),
            );
        }

        return null;
    }

    public function resolveBySessionId(string $sessionId): ?WorkflowTrackingData
    {
        $draft = $this->draftRepository->findActiveBySession($sessionId);

        if ($draft === null) {
            return null;
        }

        $answers = is_array($draft->answers) ? $draft->answers : [];

        return new WorkflowTrackingData(
            exists: true,
            trackingNumber: $draft->tracking_number,
            status: $draft->status,
            type: $draft->workflow_type,
            createdAt: $draft->created_at?->toIso8601String(),
            updatedAt: $draft->updated_at?->toIso8601String(),
            currentStep: null,
            totalSteps: null,
            steps: $answers,
        );
    }

    private function contactRequestStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'بانتظار المعالجة',
            'resolved' => 'تمت المعالجة',
            'closed' => 'مغلقة',
            default => $status,
        };
    }

    private function draftStatusLabel(string $status): string
    {
        return match ($status) {
            'collecting_data' => 'قيد الإكمال',
            'waiting_confirmation', 'confirming' => 'بانتظار التأكيد',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            'expired' => 'منتهية الصلاحية',
            default => $status,
        };
    }
}
