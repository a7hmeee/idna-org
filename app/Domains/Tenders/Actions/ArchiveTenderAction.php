<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Actions;

use App\Domains\Tenders\Contracts\TenderRepositoryInterface;
use App\Domains\Tenders\Models\Tender;

final readonly class ArchiveTenderAction
{
    public function __construct(
        private TenderRepositoryInterface $repository,
    ) {}

    public function execute(int $id): Tender
    {
        return $this->repository->archive($id);
    }
}
