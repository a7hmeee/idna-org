<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Actions;

use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\SharedKernel\Contracts\EmergencyContactRepositoryInterface;
use App\Domains\SharedKernel\DTOs\EmergencyContactDTO;
use App\Domains\SharedKernel\Models\EmergencyContact;

final readonly class SaveEmergencyContactAction
{
    public function __construct(
        private EmergencyContactRepositoryInterface $repository,
    ) {}

    public function execute(EmergencyContactDTO $dto, ?int $id = null): EmergencyContact
    {
        $contact = $this->repository->save($dto, $id);

        MunicipalityUpdated::dispatch('emergency_contacts');

        return $contact;
    }
}
