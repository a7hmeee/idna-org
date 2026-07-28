<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Actions;

use App\Domains\Tenders\Contracts\TenderRepositoryInterface;

final readonly class RecordTenderViewAction
{
    public function __construct(
        private TenderRepositoryInterface $repository,
    ) {}

    public function execute(int $id): void
    {
        $this->repository->incrementViews($id);
    }
}
