<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\DTOs\CouncilDecisionDTO;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\CouncilDecision;

final readonly class UpdateCouncilDecisionAction
{
    public function __construct(
        private CouncilDecisionRepositoryInterface $repository,
    ) {}

    public function execute(int $id, CouncilDecisionDTO $dto): CouncilDecision
    {
        $decision = $this->repository->update($id, $dto->toArray());

        MunicipalityUpdated::dispatch('council-decisions');

        return $decision;
    }
}
