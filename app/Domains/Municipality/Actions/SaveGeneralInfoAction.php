<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\GeneralInfoDTO;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\Municipality;

final readonly class SaveGeneralInfoAction
{
    public function __construct(
        private MunicipalityRepositoryInterface $repository,
    ) {}

    public function execute(GeneralInfoDTO $dto): Municipality
    {
        $municipality = $this->repository->updateGeneralInfo($dto);

        MunicipalityUpdated::dispatch('general_info');

        return $municipality;
    }
}
