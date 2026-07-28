<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Contracts;

use App\Domains\SharedKernel\DTOs\EmergencyContactDTO;
use App\Domains\SharedKernel\Models\EmergencyContact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EmergencyContactRepositoryInterface
{
    public function getForModel(Model $model): Collection;

    public function save(EmergencyContactDTO $dto, ?int $id = null): EmergencyContact;

    public function delete(int $id): bool;
}
