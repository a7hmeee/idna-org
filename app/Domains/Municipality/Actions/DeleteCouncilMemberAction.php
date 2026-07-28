<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class DeleteCouncilMemberAction
{
    public function __construct(
        private CouncilMemberRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $result = $this->repository->delete($id);

        MunicipalityUpdated::dispatch('council-members');

        return $result;
    }
}
