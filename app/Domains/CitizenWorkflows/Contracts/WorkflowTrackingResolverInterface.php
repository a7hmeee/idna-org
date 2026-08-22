<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Contracts;

use App\Domains\CitizenWorkflows\DTOs\WorkflowTrackingData;
use App\Domains\CitizenWorkflows\DTOs\WorkflowTrackingResultData;

interface WorkflowTrackingResolverInterface
{
    public function resolveByTrackingNumber(string $trackingNumber): ?WorkflowTrackingResultData;

    public function resolveBySessionId(string $sessionId): ?WorkflowTrackingData;
}
