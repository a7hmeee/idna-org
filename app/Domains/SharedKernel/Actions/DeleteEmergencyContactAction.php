<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Actions;

use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\SharedKernel\Contracts\EmergencyContactRepositoryInterface;

final readonly class DeleteEmergencyContactAction
{
    public function __construct(
        private EmergencyContactRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $result = $this->repository->delete($id);

        MunicipalityUpdated::dispatch('emergency_contacts');

        return $result;
    }
}
