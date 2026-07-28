<?php

declare(strict_types=1);

namespace App\Domains\Projects\Actions;

use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\DTOs\ProjectData;
use App\Domains\Projects\Models\Project;

final readonly class CreateProjectAction
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
    ) {}

    public function execute(ProjectData $dto): Project
    {
        return $this->repository->create($dto->toArray());
    }
}
