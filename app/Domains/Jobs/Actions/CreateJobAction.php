<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Actions;

use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use App\Domains\Jobs\DTOs\JobData;
use App\Domains\Jobs\Models\Job;

final readonly class CreateJobAction
{
    public function __construct(
        private JobRepositoryInterface $repository,
    ) {}

    public function execute(JobData $dto): Job
    {
        return $this->repository->create($dto->toArray());
    }
}
