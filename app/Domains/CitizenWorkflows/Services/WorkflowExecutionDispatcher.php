<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Services;

use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Exceptions\WorkflowIncompleteDataException;
use App\Domains\Complaints\Actions\CreateComplaintAction;
use App\Domains\Complaints\DTOs\ComplaintData;
use App\Domains\Complaints\Enums\ComplaintCategory;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\ContactRequests\Actions\CreateContactRequestAction;
use App\Domains\ContactRequests\DTOs\CreateContactRequestData;
use App\Domains\ContactRequests\Models\ContactRequest;

class WorkflowExecutionDispatcher
{
    public function __construct(
        private CreateComplaintAction $createComplaintAction,
        private CreateContactRequestAction $createContactRequestAction,
    ) {}

    public function execute(WorkflowType $type, array $data): Complaint|ContactRequest
    {
        $this->assertRequiredData($type, $data);

        return match ($type) {
            WorkflowType::Complaint => $this->executeComplaint($data),
            WorkflowType::ContactRequest => $this->executeContactRequest($data),
        };
    }

    /**
     * Last-line-of-defense guard before persistence: a workflow can only be
     * executed when every required step has a non-empty answer. Never create
     * a Complaint or a ContactRequest with missing required data — regardless
     * of any upstream confirmation/intent bug.
     */
    private function assertRequiredData(WorkflowType $type, array $data): void
    {
        $required = CitizenWorkflowRouter::STEP_DEFINITIONS[$type->value]['steps'] ?? [];

        foreach ($required as $field) {
            $value = $data[$field] ?? '';

            if (! is_string($value) || trim($value) === '') {
                throw new WorkflowIncompleteDataException($field);
            }
        }
    }

    private function executeComplaint(array $data): Complaint
    {
        $category = $this->resolveCategory($data['category'] ?? 'أخرى');

        $dto = new ComplaintData(
            citizenName: $data['citizen_name'] ?? $data['name'] ?? '',
            phone: $data['phone'] ?? '',
            category: $category,
            subject: $data['subject'] ?? '',
            description: $data['description'] ?? '',
            priority: ComplaintPriority::Medium,
            status: ComplaintStatus::Submitted,
        );

        return $this->createComplaintAction->execute($dto);
    }

    private function executeContactRequest(array $data): ContactRequest
    {
        $dto = new CreateContactRequestData(
            name: $data['name'] ?? '',
            phone: $data['phone'] ?? '',
            message: $data['message'] ?? '',
            email: $data['email'] ?? null,
            source: 'chatbot',
            department: $data['department'] ?? null,
            sessionId: $data['session_id'] ?? null,
            userId: $data['user_id'] ?? null,
        );

        return $this->createContactRequestAction->execute($dto);
    }

    private function resolveCategory(string $category): ComplaintCategory
    {
        $categoryMap = [
            'خدمات' => ComplaintCategory::Service,
            'service' => ComplaintCategory::Service,
            'بنية تحتية' => ComplaintCategory::Infrastructure,
            'infrastructure' => ComplaintCategory::Infrastructure,
            'مياه' => ComplaintCategory::Water,
            'water' => ComplaintCategory::Water,
            'كهرباء' => ComplaintCategory::Electricity,
            'electricity' => ComplaintCategory::Electricity,
            'طرق' => ComplaintCategory::Roads,
            'roads' => ComplaintCategory::Roads,
            'صرف صحي' => ComplaintCategory::Sanitation,
            'sanitation' => ComplaintCategory::Sanitation,
            'بيئة' => ComplaintCategory::Environment,
            'environment' => ComplaintCategory::Environment,
            'ضوضاء' => ComplaintCategory::Noise,
            'noise' => ComplaintCategory::Noise,
            'إداري' => ComplaintCategory::Administrative,
            'اداري' => ComplaintCategory::Administrative,
            'administrative' => ComplaintCategory::Administrative,
        ];

        $normalized = trim(mb_strtolower($category));

        return $categoryMap[$normalized] ?? ComplaintCategory::Other;
    }
}
