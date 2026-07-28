<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Actions;

use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\CustomFieldDTO;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\Municipality\Models\MunicipalityCustomField;

final readonly class SaveCustomFieldAction
{
    public function __construct(
        private MunicipalityRepositoryInterface $repository,
    ) {}

    public function execute(CustomFieldDTO $dto, ?int $id = null): MunicipalityCustomField
    {
        $field = $this->repository->saveCustomField($dto, $id);

        MunicipalityUpdated::dispatch('custom_fields');

        return $field;
    }
}
