<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\DeleteSocialPlatformAction;
use App\Domains\Municipality\Actions\SaveSocialPlatformAction;
use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\DTOs\SocialPlatformDTO;
use App\Domains\Municipality\Enums\SocialPlatformSlug;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class MunicipalitySocial extends Component
{
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $slug = '';
    public string $icon = '';
    public string $url = '';
    public ?string $color = null;
    public int $displayOrder = 0;
    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('view', Municipality::class);
    }

    public function openCreateModal(): void
    {
        $this->authorize('createSocial', Municipality::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditModal(int $id): void
    {
        $this->authorize('updateSocial', Municipality::class);

        $platform = app(MunicipalityRepositoryInterface::class)->findSocialPlatform($id);

        if ($platform) {
            $this->editingId = $id;
            $this->name = $platform->name;
            $this->slug = $platform->slug;
            $this->icon = $platform->icon;
            $this->url = $platform->url;
            $this->color = $platform->color;
            $this->displayOrder = $platform->display_order;
            $this->isActive = $platform->is_active;
            $this->showForm = true;
        }
    }

    public function save(SaveSocialPlatformAction $action): void
    {
        $this->authorize($this->editingId ? 'updateSocial' : 'createSocial', Municipality::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', \Illuminate\Validation\Rule::in(SocialPlatformSlug::values())],
            'icon' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:2000'],
            'color' => ['nullable', 'string', 'max:50'],
            'displayOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['required', 'boolean'],
        ]);

        $action->execute(SocialPlatformDTO::fromRequest($validated), $this->editingId);

        $this->showForm = false;
        $this->resetForm();
        session()->flash('success', 'تم حفظ المنصة الاجتماعية بنجاح.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('deleteSocial', Municipality::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteSocialPlatformAction $action): void
    {
        $this->authorize('deleteSocial', Municipality::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف المنصة الاجتماعية بنجاح.');
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
        $this->reset(['name', 'slug', 'icon', 'url', 'color', 'displayOrder', 'isActive', 'editingId']);
        $this->isActive = true;
    }

    public function render()
    {
        return view('livewire.municipality.social', [
            'platforms' => app(MunicipalityRepositoryInterface::class)->getSocialPlatforms(),
            'slugOptions' => SocialPlatformSlug::options(),
        ]);
    }
}
