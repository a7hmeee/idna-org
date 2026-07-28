<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Actions;

use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;

final readonly class DeleteComplaintAction
{
    public function __construct(
        private ComplaintRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}