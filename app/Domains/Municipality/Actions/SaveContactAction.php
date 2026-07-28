<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\ContactDTO;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\MunicipalityContact;

final readonly class SaveContactAction
{
    public function __construct(
        private MunicipalityRepositoryInterface $repository,
    ) {}

    public function execute(ContactDTO $dto, ?int $id = null): MunicipalityContact
    {
        $contact = $this->repository->saveContact($dto, $id);

        MunicipalityUpdated::dispatch('contacts');

        return $contact;
    }
}
