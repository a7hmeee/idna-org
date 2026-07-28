<?php

declare(strict_types=1);

namespace App\Domains\Projects\Actions;

use App\Domains\Projects\Contracts\ProjectRepositoryInterface;

final readonly class RecordProjectViewAction
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
    ) {}

    public function execute(int $id): void
    {
        $this->repository->incrementViews($id);
    }
}
