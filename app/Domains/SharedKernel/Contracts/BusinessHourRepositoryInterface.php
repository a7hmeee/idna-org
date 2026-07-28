<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Contracts;

use App\Domains\SharedKernel\DTOs\BusinessHourDTO;
use App\Domains\SharedKernel\Models\BusinessHour;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BusinessHourRepositoryInterface
{
    public function getForModel(Model $model): Collection;

    public function save(BusinessHourDTO $dto, ?int $id = null): BusinessHour;

    public function delete(int $id): bool;
}
