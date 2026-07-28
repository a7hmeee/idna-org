<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Actions;

use App\Domains\ElectronicServices\Contracts\ServiceAnalyticsRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;

final readonly class RecordPortalClickAction
{
    public function __construct(
        private ServiceAnalyticsRepositoryInterface $repository,
    ) {}

    public function execute(ElectronicService $service, array $requestData): void
    {
        $this->repository->recordPortalClick($service, $requestData);
    }
}
