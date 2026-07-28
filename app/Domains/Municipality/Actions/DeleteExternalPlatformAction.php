<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class DeleteExternalPlatformAction
{
    public function __construct(
        private MunicipalityRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $result = $this->repository->deleteExternalPlatform($id);

        MunicipalityUpdated::dispatch('external_platforms');

        return $result;
    }
}
