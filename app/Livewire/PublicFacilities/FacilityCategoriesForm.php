<?php

declare(strict_types=1);

namespace App\Livewire\PublicFacilities;

use App\Domains\PublicFacilities\Actions\CreateFacilityCategoryAction;
use App\Domains\PublicFacilities\Actions\UpdateFacilityCategoryAction;
use App\Domains\PublicFacilities\DTOs\FacilityCategoryData;
use App\Domains\PublicFacilities\Models\FacilityCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class FacilityCategoriesForm extends Component
{
    public ?int $categoryId = null;

    public string $name = '';

    public string $icon = '';

    public string $description = '';

    public int $displayOrder = 0;

    public bool $isActive = true;

    public function mount(?FacilityCategory $category = null): void
    {
        if ($category && $category->exists) {
            $this->authorize('update', FacilityCategory::class);

            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->icon = $category->icon ?? '';
            $this->description = $category->description ?? '';
            $this->displayOrder = $category->display_order;
            $this->isActive = $category->is_active;
        } else {
            $this->authorize('create', FacilityCategory::class);

            $this->displayOrder = 0;
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'displayOrder' => ['nullable', 'integer', 'min:0'],
            'isActive' => ['boolean'],
        ]);

        $dto = FacilityCategoryData::fromRequest($data);

        if ($this->categoryId) {
            app(UpdateFacilityCategoryAction::class)->execute($this->categoryId, $dto);
            session()->flash('success', 'تم تحديث التصنيف بنجاح.');
        } else {
            app(CreateFacilityCategoryAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة التصنيف بنجاح.');
        }

        $this->redirect(route('dashboard.facilities.categories'), navigate: true);
    }

    public function render()
    {
        return view('livewire.public-facilities.facility-categories-form');
    }
}
