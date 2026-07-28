<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Actions;

use App\Domains\Tenders\Contracts\TenderRepositoryInterface;
use App\Domains\Tenders\DTOs\TenderData;
use App\Domains\Tenders\Models\Tender;

final readonly class UpdateTenderAction
{
    public function __construct(
        private TenderRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenderData $dto): Tender
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
