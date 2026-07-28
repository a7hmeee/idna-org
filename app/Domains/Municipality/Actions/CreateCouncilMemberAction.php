<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\DTOs\CouncilMemberDTO;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\CouncilMember;

final readonly class CreateCouncilMemberAction
{
    public function __construct(
        private CouncilMemberRepositoryInterface $repository,
    ) {}

    public function execute(CouncilMemberDTO $dto): CouncilMember
    {
        $member = $this->repository->create($dto->toArray());

        MunicipalityUpdated::dispatch('council-members');

        return $member;
    }
}
