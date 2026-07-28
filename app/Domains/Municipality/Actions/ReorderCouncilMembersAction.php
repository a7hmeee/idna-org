<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class ReorderCouncilMembersAction
{
    public function __construct(
        private CouncilMemberRepositoryInterface $repository,
    ) {}

    public function execute(array $ids): bool
    {
        $result = $this->repository->reorder($ids);

        MunicipalityUpdated::dispatch('council-members');

        return $result;
    }
}
