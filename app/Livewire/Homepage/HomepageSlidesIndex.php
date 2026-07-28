<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Actions\CacheForgetHomepageDataAction;
use App\Domains\Homepage\Actions\DeleteHomepageSlideAction;
use App\Domains\Homepage\Actions\ToggleHomepageSlideAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class HomepageSlidesIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('viewSlides', HomepageSetting::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('deleteSlide', HomepageSetting::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteHomepageSlideAction $action): void
    {
        $this->authorize('deleteSlide', HomepageSetting::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الشريحة بنجاح.');
    }

    public function toggle(int $id, ToggleHomepageSlideAction $action): void
    {
        $this->authorize('updateSlide', HomepageSetting::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة الشريحة بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $slides = app(HomepageRepositoryInterface::class)
            ->paginateSlides(
                search: $this->search ?: null,
                status: $this->status ?: null,
            );

        return view('livewire.homepage.slides-index', [
            'slides' => $slides,
        ]);
    }
}
