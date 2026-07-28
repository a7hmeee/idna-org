<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Actions;

use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;

final readonly class PublishElectronicServiceAction
{
    public function __construct(
        private ElectronicServiceRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ElectronicService
    {
        return $this->repository->publish($id);
    }
}
