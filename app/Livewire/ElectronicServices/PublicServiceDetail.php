<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\ElectronicServices\Actions\RecordPortalClickAction;
use App\Domains\ElectronicServices\Actions\RecordServiceViewAction;
use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

final class PublicServiceDetail extends Component
{
    public ElectronicService $service;

    public Collection $relatedServices;

    public function mount(ElectronicService $service): void
    {
        abort_unless($service->is_public && $service->status === 'active', 404);

        $this->service = $service->load(['category', 'department']);

        $this->trackView();

        $repo = app(ElectronicServiceRepositoryInterface::class);
        $related = $repo->getRelatedServices(
            $this->service->service_category_id,
            $this->service->id,
            3
        );
        $this->relatedServices = $related;
    }

    private function trackView(): void
    {
        $request = request();

        app(RecordServiceViewAction::class)->execute(
            $this->service,
            [
                'ip_hash' => $request->ip() ? sha1($request->ip()) : null,
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
            ]
        );

        $this->service = $this->service->fresh()->load(['category', 'department']);
    }

    public function goToPortal(): void
    {
        $request = request();

        app(RecordPortalClickAction::class)->execute(
            $this->service,
            [
                'ip_hash' => $request->ip() ? sha1($request->ip()) : null,
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
            ]
        );

        $this->service = $this->service->fresh()->load(['category', 'department']);

        if ($this->service->portal_url) {
            $this->redirect($this->service->portal_url);
        }
    }

    public function render()
    {
        $statusLabel = $this->service->is_public && $this->service->status === 'active' ? 'متاحة' : 'غير متاحة';

        $municipalityName = 'بلدية إذنا';
        $portalUrl = null;
        try {
            $homeRepo = app(\App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface::class);
            $homeData = $homeRepo->getHomePageData();
            $municipalityName = ($homeData['municipality']['name_ar'] ?? $homeData['settings']['site_title'] ?? 'بلدية إذنا');
            $portalUrl = $homeData['settings']['portal_url'] ?? null;
        } catch (\Throwable $e) {
            // Fallback
        }

        return view('livewire.electronic-services.public-service-detail', [
            'statusLabel' => $statusLabel,
            'relatedServices' => $this->relatedServices,
            'portalUrl' => $portalUrl,
        ])->layout('layouts.home', [
            'title' => $this->service->name . ' | ' . $municipalityName,
            'metaDescription' => $this->service->summary ?? 'تفاصيل خدمة ' . $this->service->name,
        ]);
    }
}
