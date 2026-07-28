<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Actions;

use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\DTOs\ElectronicServiceData;
use App\Domains\ElectronicServices\Models\ElectronicService;

final readonly class CreateElectronicServiceAction
{
    public function __construct(
        private ElectronicServiceRepositoryInterface $repository,
    ) {}

    public function execute(ElectronicServiceData $dto): ElectronicService
    {
        return $this->repository->create($dto->toArray());
    }
}
