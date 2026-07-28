<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Actions;

use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;

final readonly class ChangeStatusAction
{
    public function __construct(
        private ComplaintRepositoryInterface $repository,
    ) {}

    public function execute(int $id, ComplaintStatus $status): Complaint
    {
        return $this->repository->changeStatus($id, $status);
    }
}