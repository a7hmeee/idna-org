<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\DTOs\ServiceSearchDocumentData;
use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\ElectronicServices\Models\ServiceSearchTerm;

final readonly class MunicipalityServiceQueryAdapter implements MunicipalityServiceQueryInterface
{
    public function __construct(
        private ElectronicServiceRepositoryInterface $serviceRepository,
        private ServiceCategoryRepositoryInterface $categoryRepository,
        private ArabicTextNormalizer $normalizer,
    ) {}

    public function getPublishedServiceCategories(): array
    {
        $categories = $this->categoryRepository->getPublicCategories();

        return $categories->map(fn (ServiceCategory $cat): array => [
            'id' => $cat->id,
            'name' => $cat->name,
            'slug' => $cat->slug,
            'description' => $cat->description,
            'service_count' => $cat->services_count ?? 0,
        ])->toArray();
    }

    /**
     * Electronic services are the services published under the electronic
     * services category. The category is located by its stable slug first,
     * then by its normalized name — never by a hardcoded numeric id.
     */
    public function getPublishedElectronicServices(): array
    {
        $categoryId = $this->electronicCategoryId();

        if ($categoryId === null) {
            return [];
        }

        return $this->getPublishedServicesByCategory($categoryId);
    }

    private function electronicCategoryId(): ?int
    {
        $category = $this->categoryRepository->findBySlug('alkhdmat-alalktrony');

        if ($category === null) {
            $normalizedTarget = $this->normalizer->normalize('الخدمات الإلكترونية');

            foreach ($this->categoryRepository->getPublicCategories() as $candidate) {
                if ($this->normalizer->normalize($candidate->name) === $normalizedTarget) {
                    $category = $candidate;
                    break;
                }
            }
        }

        return $category?->id;
    }

    public function getPublishedServicesByCategory(int $categoryId): array
    {
        $services = $this->serviceRepository->getByCategory($categoryId);

        return $services->map(fn (ElectronicService $svc): array => [
            'id' => $svc->id,
            'name' => $svc->name,
            'slug' => $svc->slug,
            'summary' => $svc->summary,
            'category_id' => $svc->service_category_id,
            'category_name' => $svc->category?->name ?? null,
        ])->toArray();
    }

    public function getCategoryById(int $categoryId): ?array
    {
        $category = $this->categoryRepository->find($categoryId);

        if ($category === null) {
            return null;
        }

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ];
    }

    public function findPublishedByExactName(string $normalizedName): ?ResolvedServiceData
    {
        $services = $this->serviceRepository->getPublicServices();

        foreach ($services as $service) {
            $normalizedServiceName = app(ArabicTextNormalizer::class)->normalize($service->name);
            if ($normalizedServiceName === $normalizedName) {
                return $this->mapToResolvedData($service);
            }
        }

        return null;
    }

    public function findPublishedByText(string $normalizedText): ?ResolvedServiceData
    {
        $services = $this->serviceRepository->getPublicServices();

        foreach ($services as $service) {
            $normalizedServiceName = app(ArabicTextNormalizer::class)->normalize($service->name);
            if (str_contains($normalizedText, $normalizedServiceName)) {
                return $this->mapToResolvedData($service);
            }
        }

        return null;
    }

    public function searchPublished(string $query, int $limit = 5): array
    {
        $paginator = $this->serviceRepository->searchPublicServices($query, null, $limit);

        return collect($paginator->items())
            ->map(fn ($service) => $this->mapToResolvedData($service))
            ->values()
            ->toArray();
    }

    public function getPublishedOverview(int $serviceId): ?ResolvedServiceData
    {
        $service = $this->serviceRepository->find($serviceId);
        if (! $service || ! $service->is_public || $service->status !== 'active') {
            return null;
        }

        return $this->mapToResolvedData($service);
    }

    public function getPublishedApplicationGuide(int $serviceId): ?ResolvedServiceData
    {
        return $this->getPublishedOverview($serviceId);
    }

    public function getPublishedRequirements(int $serviceId): ?ResolvedServiceData
    {
        return $this->getPublishedOverview($serviceId);
    }

    public function getPublishedFees(int $serviceId): ?ResolvedServiceData
    {
        return $this->getPublishedOverview($serviceId);
    }

    public function getPublishedDuration(int $serviceId): ?ResolvedServiceData
    {
        return $this->getPublishedOverview($serviceId);
    }

    public function getPublishedLocation(int $serviceId): ?ResolvedServiceData
    {
        $service = $this->serviceRepository->find($serviceId);
        if (! $service || ! $service->is_public || $service->status !== 'active') {
            return null;
        }

        $location = null;
        if ($service->relationLoaded('department') && $service->department) {
            $location = $service->department->name;
        }

        return new ResolvedServiceData(
            id: $service->id,
            name: $service->name,
            slug: $service->slug,
            description: $service->description,
            summary: $service->summary,
            steps: $service->steps ?? [],
            requirements: $service->requirements ?? [],
            documents: $service->documents ?? [],
            fees: $service->fees,
            processingTime: $service->processing_time,
            location: $location,
            portalUrl: $service->portal_url,
            departmentName: $location,
        );
    }

    public function getPublishedOnlineLink(int $serviceId): ?ResolvedServiceData
    {
        return $this->getPublishedOverview($serviceId);
    }

    public function getSearchDocuments(): array
    {
        $electronicCategoryId = $this->electronicCategoryId();

        // Only electronic services are offered in the chat — the municipal
        // catalog services are not part of the guided or smart search.
        $services = $this->serviceRepository->getPublicServices()
            ->filter(fn (ElectronicService $svc): bool => $svc->service_category_id === $electronicCategoryId);

        $serviceIds = $services->pluck('id')->all();

        $allTerms = ServiceSearchTerm::query()
            ->whereIn('electronic_service_id', $serviceIds)
            ->orderBy('priority')
            ->get(['electronic_service_id', 'term', 'type'])
            ->toArray();

        $termsByService = [];
        foreach ($allTerms as $term) {
            $sid = $term['electronic_service_id'];
            if (! isset($termsByService[$sid])) {
                $termsByService[$sid] = [];
            }
            $termsByService[$sid][] = $term;
        }

        $normalizer = app(ArabicTextNormalizer::class);
        $documents = [];

        foreach ($services as $service) {
            $terms = $termsByService[$service->id] ?? [];
            $aliases = [];
            $keywords = [];
            $phrases = [];
            $citizenExprs = [];

            foreach ($terms as $term) {
                $t = $term['term'];
                switch ($term['type']) {
                    case 'alias':
                        $aliases[] = $t;
                        break;
                    case 'keyword':
                        $keywords[] = $t;
                        break;
                    case 'phrase':
                        $phrases[] = $t;
                        break;
                    case 'citizen_expression':
                        $citizenExprs[] = $t;
                        break;
                }
            }

            $normalizedOfficialName = $normalizer->normalize($service->name);
            $categoryName = $service->relationLoaded('category') && $service->category
                ? $service->category->name
                : null;

            $documents[] = new ServiceSearchDocumentData(
                serviceId: $service->id,
                officialName: $service->name,
                normalizedOfficialName: $normalizedOfficialName,
                aliases: $aliases,
                normalizedAliases: array_map(fn (string $a) => $normalizer->normalize($a), $aliases),
                keywords: $keywords,
                normalizedKeywords: array_map(fn (string $k) => $normalizer->normalize($k), $keywords),
                searchablePhrases: $phrases,
                normalizedSearchablePhrases: array_map(fn (string $p) => $normalizer->normalize($p), $phrases),
                citizenExpressions: $citizenExprs,
                normalizedCitizenExpressions: array_map(fn (string $e) => $normalizer->normalize($e), $citizenExprs),
                shortDescription: $service->summary,
                normalizedShortDescription: $service->summary ? $normalizer->normalize($service->summary) : null,
                categoryName: $categoryName,
                normalizedCategoryName: $categoryName ? $normalizer->normalize($categoryName) : null,
                priority: $service->sort_order ?? 0,
                isPublished: $service->is_public && $service->status === 'active',
                applicationUrl: $service->portal_url,
                updatedAt: $service->updated_at?->format('Y-m-d H:i:s'),
            );
        }

        return $documents;
    }

    private function mapToResolvedData($service): ResolvedServiceData
    {
        $location = null;
        if ($service->relationLoaded('department') && $service->department) {
            $location = $service->department->name;
        }

        return new ResolvedServiceData(
            id: $service->id,
            name: $service->name,
            slug: $service->slug,
            description: $service->description,
            summary: $service->summary,
            steps: $service->steps ?? [],
            requirements: $service->requirements ?? [],
            documents: $service->documents ?? [],
            fees: $service->fees,
            processingTime: $service->processing_time,
            location: $location,
            portalUrl: $service->portal_url,
            departmentName: $location,
        );
    }
}
