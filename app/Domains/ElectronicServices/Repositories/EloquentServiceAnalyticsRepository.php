<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Repositories;

use App\Domains\ElectronicServices\Contracts\ServiceAnalyticsRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServicePortalClick;
use App\Domains\ElectronicServices\Models\ServiceView;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class EloquentServiceAnalyticsRepository implements ServiceAnalyticsRepositoryInterface
{
    public function recordView(ElectronicService $service, array $requestData): void
    {
        DB::transaction(function () use ($service, $requestData): void {
            ServiceView::create([
                'electronic_service_id' => $service->id,
                'ip_hash' => $requestData['ip_hash'] ?? null,
                'user_agent' => $requestData['user_agent'] ?? null,
                'referrer' => $requestData['referrer'] ?? null,
                'viewed_at' => now(),
            ]);

            $service->increment('views_count');
        });
    }

    public function recordPortalClick(ElectronicService $service, array $requestData): void
    {
        DB::transaction(function () use ($service, $requestData): void {
            ServicePortalClick::create([
                'electronic_service_id' => $service->id,
                'ip_hash' => $requestData['ip_hash'] ?? null,
                'user_agent' => $requestData['user_agent'] ?? null,
                'referrer' => $requestData['referrer'] ?? null,
                'clicked_at' => now(),
            ]);

            $service->increment('portal_clicks_count');
        });
    }

    public function topViewedServices(int $limit = 10): Collection
    {
        return ElectronicService::with(['category:id,name', 'department:id,name'])
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }

    public function topClickedServices(int $limit = 10): Collection
    {
        return ElectronicService::with(['category:id,name', 'department:id,name'])
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderByDesc('portal_clicks_count')
            ->limit($limit)
            ->get();
    }

    public function conversionRate(int $serviceId): float
    {
        $service = ElectronicService::select('id', 'views_count', 'portal_clicks_count')->findOrFail($serviceId);

        if ($service->views_count === 0) {
            return 0.0;
        }

        return round(($service->portal_clicks_count / $service->views_count) * 100, 2);
    }

    public function dashboardStats(): array
    {
        $totalServices = ElectronicService::count();
        $totalViews = ElectronicService::sum('views_count');
        $totalClicks = ElectronicService::sum('portal_clicks_count');
        $activeServices = ElectronicService::where('status', 'active')->count();

        $overallConversion = $totalViews > 0
            ? round(($totalClicks / $totalViews) * 100, 2)
            : 0.0;

        return [
            'total_services' => $totalServices,
            'active_services' => $activeServices,
            'total_views' => $totalViews,
            'total_clicks' => $totalClicks,
            'conversion_rate' => $overallConversion,
        ];
    }
}
