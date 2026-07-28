<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Repositories;

use App\Domains\PublicFacilities\Contracts\FacilityCategoryRepositoryInterface;
use App\Domains\PublicFacilities\Models\FacilityCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class EloquentFacilityCategoryRepository implements FacilityCategoryRepositoryInterface
{
    public function __construct(
        private FacilityCategory $model,
    ) {}

    public function paginateDashboard(): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15);
    }

    public function find(int $id): ?FacilityCategory
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?FacilityCategory
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): FacilityCategory
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): FacilityCategory
    {
        $category = $this->model->findOrFail($id);
        $category->update($data);

        return $category->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    public function getAll(): Collection
    {
        return $this->model
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }
}
