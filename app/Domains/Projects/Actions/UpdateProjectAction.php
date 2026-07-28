<?php

declare(strict_types=1);

namespace App\Domains\Projects\Actions;

use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\DTOs\ProjectData;
use App\Domains\Projects\Models\Project;

final readonly class UpdateProjectAction
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
    ) {}

    public function execute(int $id, ProjectData $dto): Project
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
