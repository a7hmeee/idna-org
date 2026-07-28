<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Actions;

use App\Domains\Tenders\Contracts\TenderRepositoryInterface;
use App\Domains\Tenders\DTOs\TenderData;
use App\Domains\Tenders\Models\Tender;

final readonly class CreateTenderAction
{
    public function __construct(
        private TenderRepositoryInterface $repository,
    ) {}

    public function execute(TenderData $dto): Tender
    {
        return $this->repository->create($dto->toArray());
    }
}
