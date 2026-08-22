<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Repositories;

use App\Domains\OpenData\Contracts\OpenDataRepositoryInterface;
use App\Domains\OpenData\Enums\OpenDataType;
use App\Domains\OpenData\Models\OpenDataset;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class EloquentOpenDataRepository implements OpenDataRepositoryInterface
{
    public function getDatasets(?string $search = null, ?string $category = null, ?OpenDataType $type = null): LengthAwarePaginator
    {
        $query = OpenDataset::published()->orderBy('published_at', 'desc');

        if ($type) {
            $query->ofType($type);
        }

        if ($search && strlen($search) >= 2) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        return $query->paginate(12);
    }

    public function find(int $id): ?OpenDataset
    {
        return OpenDataset::find($id);
    }

    public function findBySlug(string $slug): ?OpenDataset
    {
        return OpenDataset::where('slug', $slug)->published()->first();
    }

    public function getFeaturedDatasets(): Collection
    {
        return OpenDataset::published()->featured()->orderBy('display_order')->orderBy('published_at', 'desc')->take(6)->get();
    }

    public function getLatestDatasets(int $limit = 5): Collection
    {
        return OpenDataset::published()->orderBy('published_at', 'desc')->take($limit)->get();
    }

    public function getCategories(): Collection
    {
        return OpenDataset::published()->whereNotNull('category')->select('category')->distinct()->orderBy('category')->pluck('category');
    }

    public function downloadDataset(int $id): string
    {
        $dataset = $this->find($id);

        if (! $dataset || ! $dataset->download_url) {
            throw new \RuntimeException('No dataset found');
        }

        return $dataset->download_url;
    }
}
