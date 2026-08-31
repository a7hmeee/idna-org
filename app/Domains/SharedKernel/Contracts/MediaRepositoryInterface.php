<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Contracts;

use App\Domains\SharedKernel\DTOs\MediaDTO;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface MediaRepositoryInterface
{
    public function getForModel(Model $model, ?string $collection = null): Collection;

    public function paginateForModel(Model $model, ?string $collection = null, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Media;

    public function save(MediaDTO $dto, ?int $id = null): Media;

    public function delete(int $id): bool;

    /**
     * Search and filter media with advanced options.
     *
     * @param array{
     *     search?: string,
     *     collection?: string,
     *     type?: string,
     *     status?: string,
     *     sort?: string,
     * } $filters
     */
    public function search(Model $model, array $filters = [], int $perPage = 20): LengthAwarePaginator;
}
