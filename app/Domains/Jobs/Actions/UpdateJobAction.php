<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Actions;

use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use App\Domains\Jobs\DTOs\JobData;
use App\Domains\Jobs\Models\Job;

final readonly class UpdateJobAction
{
    public function __construct(
        private JobRepositoryInterface $repository,
    ) {}

    public function execute(int $id, JobData $dto): Job
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
