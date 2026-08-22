<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Services;

use App\Domains\Chatbot\Contracts\FacilityQueryInterface;
use App\Domains\Chatbot\DTOs\FacilityDetailsData;
use App\Domains\Chatbot\DTOs\FacilitySummaryData;
use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use Illuminate\Support\Facades\Cache;

final readonly class FacilityQueryAdapter implements FacilityQueryInterface
{
    private const CACHE_KEY = 'chatbot:facilities';

    private const CACHE_TTL = 3600;

    public function __construct(
        private FacilityRepositoryInterface $repository,
    ) {}

    public function getPublishedFacilities(int $limit = 10): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () use ($limit): array {
            $facilities = $this->repository->getPublished()->items();

            return collect($facilities)
                ->take($limit)
                ->map(fn ($facility) => new FacilitySummaryData(
                    id: (int) $facility->id,
                    name: $facility->name,
                    slug: $facility->slug,
                    categoryName: $facility->category?->name,
                    summary: $facility->summary,
                    phone: $facility->phone,
                    address: $facility->address,
                    status: $facility->status->value,
                ))
                ->values()
                ->all();
        });
    }

    public function searchPublishedFacilities(string $query, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY);

        $facilities = $this->repository->getPublished();

        return collect($facilities)
            ->filter(fn ($facility) => str_contains(mb_strtolower($facility->name), mb_strtolower($query))
                || str_contains(mb_strtolower($facility->summary ?? ''), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($facility) => new FacilitySummaryData(
                id: (int) $facility->id,
                name: $facility->name,
                slug: $facility->slug,
                categoryName: $facility->category?->name,
                summary: $facility->summary,
                phone: $facility->phone,
                address: $facility->address,
                status: $facility->status->value,
            ))
            ->values()
            ->all();
    }

    public function getPublishedFacilityById(int $id): ?FacilityDetailsData
    {
        $facility = $this->repository->find($id);

        if ($facility === null || $facility->status->value !== 'published' || ! $facility->is_public) {
            return null;
        }

        return new FacilityDetailsData(
            id: (int) $facility->id,
            name: $facility->name,
            slug: $facility->slug,
            categoryName: $facility->category?->name,
            summary: $facility->summary,
            description: $facility->description,
            phone: $facility->phone,
            email: $facility->email,
            address: $facility->address,
            workingHours: $facility->working_hours,
            services: $facility->services ?? [],
            features: $facility->features ?? [],
            status: $facility->status->value,
        );
    }
}
