<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Exceptions;

use RuntimeException;

final class WorkflowIncompleteDataException extends RuntimeException
{
    public function __construct(
        public readonly string $missingStep,
    ) {
        parent::__construct("Workflow cannot be persisted: missing required data for step [{$missingStep}].");
    }
}
