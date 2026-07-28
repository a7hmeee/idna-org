<?php

declare(strict_types=1);

namespace App\Livewire\PageCarousels;

use App\Domains\Homepage\Actions\DeleteHomepageSlideAction;
use App\Domains\Homepage\Actions\ToggleHomepageSlideAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Enums\PageCarouselKey;
use App\Domains\Homepage\Models\HomepageSetting;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class PageCarouselsIndex extends Component
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
        $allSlides = \App\Domains\Homepage\Models\HomepageSlide::orderBy('sort_order')->get();

        $currentPage = (int) request()->input('page', 1);
        $perPage = 10;
        $offset = ($currentPage - 1) * $perPage;
        $items = $allSlides->slice($offset, $perPage);

        $slides = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $allSlides->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $debug = 'allSlides=' . $allSlides->count() . ' | items=' . $items->count();

        session()->flash('debug', $debug);

        return view('livewire.page-carousels.page-carousels-index', [
            'slides' => $slides,
        ]);
    }
}
