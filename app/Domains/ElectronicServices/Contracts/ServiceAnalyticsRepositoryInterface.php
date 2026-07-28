<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Contracts;

use App\Domains\ElectronicServices\Models\ElectronicService;
use Illuminate\Database\Eloquent\Collection;

interface ServiceAnalyticsRepositoryInterface
{
    public function recordView(ElectronicService $service, array $requestData): void;

    public function recordPortalClick(ElectronicService $service, array $requestData): void;

    public function topViewedServices(int $limit = 10): Collection;

    public function topClickedServices(int $limit = 10): Collection;

    public function conversionRate(int $serviceId): float;

    public function dashboardStats(): array;
}
