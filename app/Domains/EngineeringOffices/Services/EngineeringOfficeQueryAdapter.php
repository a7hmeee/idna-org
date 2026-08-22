<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Services;

use App\Domains\Chatbot\Contracts\EngineeringOfficeQueryInterface;
use App\Domains\Chatbot\DTOs\EngineeringOfficeDetailsData;
use App\Domains\Chatbot\DTOs\EngineeringOfficeSummaryData;
use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use App\Domains\EngineeringOffices\Enums\EngineeringOfficeApprovalStatus;
use Illuminate\Support\Facades\Cache;

final readonly class EngineeringOfficeQueryAdapter implements EngineeringOfficeQueryInterface
{
    private const CACHE_KEY = 'chatbot:engineering-offices';

    private const CACHE_TTL = 3600;

    public function __construct(
        private EngineeringOfficeRepositoryInterface $repository,
    ) {}

    public function getPublishedEngineeringOffices(int $limit = 10): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () use ($limit): array {
            $offices = $this->repository->getPublicOffices();

            return collect($offices)
                ->take($limit)
                ->map(fn ($office) => new EngineeringOfficeSummaryData(
                    id: (int) $office->id,
                    officeName: $office->office_name,
                    slug: $office->slug,
                    engineerName: $office->engineer_name,
                    phone: $office->phone ?? $office->mobile,
                    address: $office->address,
                    approvalStatus: $office->approval_status instanceof EngineeringOfficeApprovalStatus
                        ? $office->approval_status->value
                        : (string) ($office->approval_status ?? ''),
                    status: $office->status ?? 'active',
                ))
                ->values()
                ->all();
        });
    }

    public function searchPublishedEngineeringOffices(string $query, int $limit = 5): array
    {
        Cache::forget(self::CACHE_KEY);

        $offices = $this->repository->getPublicOffices();

        return collect($offices)
            ->filter(fn ($office) => str_contains(mb_strtolower($office->office_name), mb_strtolower($query))
                || str_contains(mb_strtolower($office->engineer_name ?? ''), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($office) => new EngineeringOfficeSummaryData(
                id: (int) $office->id,
                officeName: $office->office_name,
                slug: $office->slug,
                engineerName: $office->engineer_name,
                phone: $office->phone ?? $office->mobile,
                address: $office->address,
                approvalStatus: $office->approval_status instanceof EngineeringOfficeApprovalStatus
                    ? $office->approval_status->value
                    : (string) ($office->approval_status ?? ''),
                status: $office->status ?? 'active',
            ))
            ->values()
            ->all();
    }

    public function getPublishedEngineeringOfficeById(int $id): ?EngineeringOfficeDetailsData
    {
        $office = $this->repository->find($id);

        if ($office === null || ! $office->is_public) {
            return null;
        }

        return new EngineeringOfficeDetailsData(
            id: (int) $office->id,
            officeName: $office->office_name,
            slug: $office->slug,
            engineerName: $office->engineer_name,
            licenseNumber: $office->license_number,
            phone: $office->phone,
            mobile: $office->mobile,
            email: $office->email,
            address: $office->address,
            specializations: $office->specializations ?? [],
            approvalStatus: $office->approval_status instanceof EngineeringOfficeApprovalStatus
                ? $office->approval_status->value
                : (string) ($office->approval_status ?? ''),
            status: $office->status ?? 'active',
            expiresAt: $office->expires_at?->format('Y-m-d'),
        );
    }
}
