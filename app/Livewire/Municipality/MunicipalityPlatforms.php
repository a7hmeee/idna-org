<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\DeleteExternalPlatformAction;
use App\Domains\Municipality\Actions\SaveExternalPlatformAction;
use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\ExternalPlatformDTO;
use App\Domains\Municipality\Enums\PlatformCategory;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class MunicipalityPlatforms extends Component
{
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public ?string $description = null;
    public string $icon = '';
    public string $url = '';
    public ?string $category = null;
    public ?string $color = null;
    public bool $openInNewTab = true;
    public bool $isFeatured = false;
    public int $displayOrder = 0;
    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('view', Municipality::class);
    }

    public function openCreateModal(): void
    {
        $this->authorize('createPlatform', Municipality::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditModal(int $id): void
    {
        $this->authorize('updatePlatform', Municipality::class);

        $platform = app(MunicipalityRepositoryInterface::class)->findExternalPlatform($id);

        if ($platform) {
            $this->editingId = $id;
            $this->name = $platform->name;
            $this->description = $platform->description;
            $this->icon = $platform->icon;
            $this->url = $platform->url;
            $this->category = $platform->category;
            $this->color = $platform->color;
            $this->openInNewTab = $platform->open_in_new_tab;
            $this->isFeatured = $platform->is_featured;
            $this->displayOrder = $platform->display_order;
            $this->isActive = $platform->is_active;
            $this->showForm = true;
        }
    }

    public function save(SaveExternalPlatformAction $action): void
    {
        $this->authorize($this->editingId ? 'updatePlatform' : 'createPlatform', Municipality::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:2000'],
            'category' => ['nullable', 'string', \Illuminate\Validation\Rule::in(PlatformCategory::values())],
            'color' => ['nullable', 'string', 'max:50'],
            'openInNewTab' => ['required', 'boolean'],
            'isFeatured' => ['required', 'boolean'],
            'displayOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['required', 'boolean'],
        ]);

        $action->execute(ExternalPlatformDTO::fromRequest($validated), $this->editingId);

        $this->showForm = false;
        $this->resetForm();
        session()->flash('success', 'تم حفظ المنصة الخارجية بنجاح.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('deletePlatform', Municipality::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteExternalPlatformAction $action): void
    {
        $this->authorize('deletePlatform', Municipality::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف المنصة الخارجية بنجاح.');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'description', 'icon', 'url', 'category', 'color', 'openInNewTab', 'isFeatured', 'displayOrder', 'isActive', 'editingId']);
        $this->openInNewTab = true;
        $this->isActive = true;
    }

    public function render()
    {
        return view('livewire.municipality.platforms', [
            'platforms' => app(MunicipalityRepositoryInterface::class)->getExternalPlatforms(),
            'categoryOptions' => PlatformCategory::options(),
        ]);
    }
}
