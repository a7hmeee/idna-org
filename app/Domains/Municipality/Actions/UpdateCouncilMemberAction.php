<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\DTOs\CouncilMemberDTO;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\CouncilMember;

final readonly class UpdateCouncilMemberAction
{
    public function __construct(
        private CouncilMemberRepositoryInterface $repository,
    ) {}

    public function execute(int $id, CouncilMemberDTO $dto): CouncilMember
    {
        $member = $this->repository->update($id, $dto->toArray());

        MunicipalityUpdated::dispatch('council-members');

        return $member;
    }
}
