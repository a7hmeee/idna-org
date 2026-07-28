<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Actions;

use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;

final readonly class RecordComplaintViewAction
{
    public function __construct(
        private ComplaintRepositoryInterface $repository,
    ) {}

    public function execute(int $id): void
    {
        $this->repository->incrementViews($id);
    }
}