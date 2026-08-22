<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\ElectronicServices\Models\ServiceCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ServiceCategoryShow extends Component
{
    public ServiceCategory $category;

    public function mount(ServiceCategory $category): void
    {
        $this->authorize('view', ServiceCategory::class);

        $this->category = $category->loadCount('services');
    }

    public function render()
    {
        return view('livewire.electronic-services.service-category-show', [
            'canUpdate' => auth()->user()->can('update', ServiceCategory::class),
            'canDelete' => auth()->user()->can('delete', ServiceCategory::class),
            'services' => $this->category->services()
                ->with('department')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
