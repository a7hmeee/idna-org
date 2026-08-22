<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Actions;

use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Models\Complaint;

final readonly class RespondToComplaintAction
{
    public function __construct(
        private ComplaintRepositoryInterface $repository,
    ) {}

    public function execute(int $id, string $publicResponse): Complaint
    {
        return $this->repository->respond($id, $publicResponse);
    }
}
