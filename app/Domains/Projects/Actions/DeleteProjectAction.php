<?php

declare(strict_types=1);

namespace App\Domains\Projects\Actions;

use App\Domains\Projects\Contracts\ProjectRepositoryInterface;

final readonly class DeleteProjectAction
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
