<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\ExternalPlatformDTO;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\MunicipalityExternalPlatform;

final readonly class SaveExternalPlatformAction
{
    public function __construct(
        private MunicipalityRepositoryInterface $repository,
    ) {}

    public function execute(ExternalPlatformDTO $dto, ?int $id = null): MunicipalityExternalPlatform
    {
        $platform = $this->repository->saveExternalPlatform($dto, $id);

        MunicipalityUpdated::dispatch('external_platforms');

        return $platform;
    }
}
