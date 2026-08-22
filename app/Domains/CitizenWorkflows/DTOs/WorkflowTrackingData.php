<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\DTOs;

final readonly class WorkflowTrackingData
{
    public function __construct(
        public bool $exists,
        public ?string $trackingNumber = null,
        public ?string $status = null,
        public ?string $type = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public ?int $currentStep = null,
        public ?int $totalSteps = null,
        public array $steps = [],
    ) {}
}
