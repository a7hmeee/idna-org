<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Actions;

use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\DTOs\ComplaintData;
use App\Domains\Complaints\Models\Complaint;

final readonly class UpdateComplaintAction
{
    public function __construct(
        private ComplaintRepositoryInterface $repository,
    ) {}

    public function execute(int $id, ComplaintData $dto): Complaint
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
