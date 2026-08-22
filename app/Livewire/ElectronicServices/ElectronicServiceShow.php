<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\ElectronicServices\Actions\ArchiveElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\PublishElectronicServiceAction;
use App\Domains\ElectronicServices\Enums\ElectronicServiceStatus;
use App\Domains\ElectronicServices\Models\ElectronicService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ElectronicServiceShow extends Component
{
    public ElectronicService $service;

    public function mount(ElectronicService $service): void
    {
        $this->authorize('view', ElectronicService::class);

        $this->service = $service->load(['category', 'department']);
    }

    public function publish(PublishElectronicServiceAction $action): void
    {
        $this->authorize('publish', ElectronicService::class);

        $action->execute($this->service->id);

        $this->service = $this->service->fresh()->load(['category', 'department']);
        session()->flash('success', 'تم نشر الخدمة بنجاح.');
    }

    public function archive(ArchiveElectronicServiceAction $action): void
    {
        $this->authorize('publish', ElectronicService::class);

        $action->execute($this->service->id);

        $this->service = $this->service->fresh()->load(['category', 'department']);
        session()->flash('success', 'تم أرشفة الخدمة بنجاح.');
    }

    public function render()
    {
        $conversionRate = $this->service->views_count > 0
            ? round(($this->service->portal_clicks_count / $this->service->views_count) * 100, 2)
            : 0;

        return view('livewire.electronic-services.electronic-service-show', [
            'statusOptions' => ElectronicServiceStatus::options(),
            'conversionRate' => $conversionRate,
            'canUpdate' => auth()->user()->can('update', ElectronicService::class),
            'canDelete' => auth()->user()->can('delete', ElectronicService::class),
            'canPublish' => auth()->user()->can('publish', ElectronicService::class),
        ]);
    }
}
