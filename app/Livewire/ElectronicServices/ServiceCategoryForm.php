<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\ElectronicServices\Actions\CreateServiceCategoryAction;
use App\Domains\ElectronicServices\Actions\UpdateServiceCategoryAction;
use App\Domains\ElectronicServices\DTOs\ServiceCategoryData;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\ElectronicServices\Requests\StoreServiceCategoryRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ServiceCategoryForm extends Component
{
    public ?int $categoryId = null;
    public ?int $parent_id = null;
    public string $name = '';
    public ?string $slug = null;
    public ?string $description = null;
    public ?string $icon = null;
    public ?string $image_path = null;
    public string $status = 'active';
    public bool $is_public = true;
    public int $sort_order = 0;

    public function mount(?ServiceCategory $category = null): void
    {
        if ($category?->exists) {
            $this->authorize('update', ServiceCategory::class);

            $this->categoryId = $category->id;
            $this->parent_id = $category->parent_id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description;
            $this->icon = $category->icon;
            $this->image_path = $category->image_path;
            $this->status = $category->status;
            $this->is_public = $category->is_public;
            $this->sort_order = $category->sort_order;
        } else {
            $this->authorize('create', ServiceCategory::class);
        }
    }

    public function save(): void
    {
        $validated = $this->validate((new StoreServiceCategoryRequest)->rules());

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $dto = ServiceCategoryData::fromRequest($validated);

        if ($this->categoryId) {
            app(UpdateServiceCategoryAction::class)->execute($this->categoryId, $dto);
            session()->flash('success', 'تم تحديث التصنيف بنجاح.');
        } else {
            $category = app(CreateServiceCategoryAction::class)->execute($dto);
            $this->categoryId = $category->id;
            session()->flash('success', 'تم إنشاء التصنيف بنجاح.');
        }

        $this->redirectRoute('dashboard.electronic-services.categories');
    }

    public function render()
    {
        return view('livewire.electronic-services.service-category-form', [
            'parentCategories' => ServiceCategory::whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }
}
