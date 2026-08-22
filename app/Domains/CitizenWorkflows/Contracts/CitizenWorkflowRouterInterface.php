<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Contracts;

use App\Domains\CitizenWorkflows\Enums\WorkflowType;

interface CitizenWorkflowRouterInterface
{
    public function getSteps(?WorkflowType $type): array;

    public function getCompletedStepsCount(WorkflowType $type, array $answers): int;

    public function getInitialQuestion(WorkflowType $type): string;

    public function getStepQuestion(WorkflowType $type, string $step): ?string;

    public function getStepLabel(WorkflowType $type, string $step): string;

    public function getStepHint(WorkflowType $type, string $step): ?string;

    public function getWorkflowLabel(WorkflowType $type): string;

    public function getStepNumber(WorkflowType $type, string $step): ?int;

    public function getConfirmationMessage(WorkflowType $type, array $data): string;

    public function getSuccessMessage(WorkflowType $type, array $data, mixed $result): string;

    public function getSuccessDetails(WorkflowType $type, mixed $result): array;

    public function getSuccessActions(WorkflowType $type, ?string $trackingNumber): array;

    public function getWorkflowStartMessage(WorkflowType $type): string;

    public function getWorkflowCancelLabel(WorkflowType $type): string;
}
