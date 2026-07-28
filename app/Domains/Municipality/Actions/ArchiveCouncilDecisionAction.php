<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\CouncilDecision;

final readonly class ArchiveCouncilDecisionAction
{
    public function __construct(
        private CouncilDecisionRepositoryInterface $repository,
    ) {}

    public function execute(int $id): CouncilDecision
    {
        $decision = $this->repository->archive($id);

        MunicipalityUpdated::dispatch('council-decisions');

        return $decision;
    }
}
