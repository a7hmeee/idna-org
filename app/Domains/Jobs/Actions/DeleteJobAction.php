<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Actions;

use App\Domains\Jobs\Contracts\JobRepositoryInterface;

final readonly class DeleteJobAction
{
    public function __construct(
        private JobRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
