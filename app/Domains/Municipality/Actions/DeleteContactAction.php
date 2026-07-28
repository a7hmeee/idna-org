<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class DeleteContactAction
{
    public function __construct(
        private MunicipalityRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $result = $this->repository->deleteContact($id);

        MunicipalityUpdated::dispatch('contacts');

        return $result;
    }
}
