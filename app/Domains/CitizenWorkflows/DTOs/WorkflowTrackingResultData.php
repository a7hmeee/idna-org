<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\DTOs;

final readonly class WorkflowTrackingResultData
{
    public function __construct(
        public string $trackingNumber,
        public string $type,
        public string $status,
        public string $statusLabel,
        public ?string $submittedDate = null,
        public ?string $lastPublicUpdate = null,
        public ?string $department = null,
        public ?string $subject = null,
    ) {}
}
