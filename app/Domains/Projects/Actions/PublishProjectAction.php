<?php

declare(strict_types=1);

namespace App\Domains\Projects\Actions;

use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\Models\Project;

final readonly class PublishProjectAction
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
    ) {}

    public function execute(int $id): Project
    {
        return $this->repository->publish($id);
    }
}
