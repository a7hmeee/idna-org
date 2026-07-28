<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\CouncilMember;

final readonly class TogglePublicCouncilMemberAction
{
    public function __construct(
        private CouncilMemberRepositoryInterface $repository,
    ) {}

    public function execute(int $id): CouncilMember
    {
        $member = $this->repository->togglePublic($id);

        MunicipalityUpdated::dispatch('council-members');

        return $member;
    }
}
