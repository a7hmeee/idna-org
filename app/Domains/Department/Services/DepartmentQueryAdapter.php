<?php

declare(strict_types=1);

namespace App\Domains\Department\Services;

use App\Domains\Chatbot\Contracts\DepartmentQueryInterface;
use App\Domains\Chatbot\DTOs\DepartmentDetailsData;
use App\Domains\Chatbot\DTOs\DepartmentSummaryData;
use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

final readonly class DepartmentQueryAdapter implements DepartmentQueryInterface
{
    private const CACHE_KEY = 'chatbot:departments';

    private const CACHE_TTL = 3600;

    public function __construct(
        private DepartmentRepositoryInterface $repository,
    ) {}

    public function getPublishedDepartments(int $limit = 10): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () use ($limit): array {
            $departments = $this->repository->getPublicDepartments();

            return collect($departments)
                ->take($limit)
                ->map(fn ($dept) => new DepartmentSummaryData(
                    id: (int) $dept->id,
                    name: $dept->name,
                    slug: $dept->slug,
                    shortDescription: $dept->short_description,
                    phone: $dept->phone,
                    email: $dept->email,
                    status: $dept->status ?? 'active',
                ))
                ->values()
                ->all();
        });
    }

    public function searchPublishedDepartments(string $query, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY);

        $departments = $this->repository->getPublicDepartments();

        return collect($departments)
            ->filter(fn ($dept) => str_contains(mb_strtolower($dept->name), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($dept) => new DepartmentSummaryData(
                id: (int) $dept->id,
                name: $dept->name,
                slug: $dept->slug,
                shortDescription: $dept->short_description,
                phone: $dept->phone,
                email: $dept->email,
                status: $dept->status ?? 'active',
            ))
            ->values()
            ->all();
    }

    public function getPublishedDepartmentById(int $id): ?DepartmentDetailsData
    {
        $department = $this->repository->find($id);

        if ($department === null || ! $department->is_public || $department->status !== 'active') {
            return null;
        }

        return new DepartmentDetailsData(
            id: (int) $department->id,
            name: $department->name,
            slug: $department->slug,
            shortDescription: $department->short_description,
            description: $department->description,
            managerName: $department->manager_name,
            managerPosition: $department->manager_position,
            phone: $department->phone,
            extension: $department->extension,
            mobile: $department->mobile,
            email: $department->email,
            officeLocation: $department->office_location,
            workingHours: $department->working_hours,
            vision: $department->vision,
            mission: $department->mission,
            responsibilities: $department->responsibilities,
            status: $department->status,
        );
    }

    public function getPublishedDepartmentByName(string $name): ?DepartmentDetailsData
    {
        $departments = $this->repository->getPublicDepartments();

        $match = collect($departments)->first(
            fn ($dept) => mb_strtolower($dept->name) === mb_strtolower($name)
        );

        if ($match === null) {
            return null;
        }

        return $this->getPublishedDepartmentById((int) $match->id);
    }
}
