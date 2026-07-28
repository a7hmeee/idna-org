<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class DeleteCouncilDecisionAction
{
    public function __construct(
        private CouncilDecisionRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $result = $this->repository->delete($id);

        MunicipalityUpdated::dispatch('council-decisions');

        return $result;
    }
}
