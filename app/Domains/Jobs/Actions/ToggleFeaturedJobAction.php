<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Actions;

use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use App\Domains\Jobs\Models\Job;

final readonly class ToggleFeaturedJobAction
{
    public function __construct(
        private JobRepositoryInterface $repository,
    ) {}

    public function execute(int $id): Job
    {
        return $this->repository->toggleFeatured($id);
    }
}
