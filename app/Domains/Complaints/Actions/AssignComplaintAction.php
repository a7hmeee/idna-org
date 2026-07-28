<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Actions;

use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Models\Complaint;

final readonly class AssignComplaintAction
{
    public function __construct(
        private ComplaintRepositoryInterface $repository,
    ) {}

    public function execute(int $id, int $userId): Complaint
    {
        return $this->repository->assign($id, $userId);
    }
}