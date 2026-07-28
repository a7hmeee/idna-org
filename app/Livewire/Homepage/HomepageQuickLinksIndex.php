<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Actions\DeleteHomepageQuickLinkAction;
use App\Domains\Homepage\Actions\ToggleHomepageQuickLinkAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class HomepageQuickLinksIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('viewQuickLinks', HomepageSetting::class);
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
        $this->authorize('deleteQuickLink', HomepageSetting::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteHomepageQuickLinkAction $action): void
    {
        $this->authorize('deleteQuickLink', HomepageSetting::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الرابط السريع بنجاح.');
    }

    public function toggle(int $id, ToggleHomepageQuickLinkAction $action): void
    {
        $this->authorize('updateQuickLink', HomepageSetting::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة الرابط السريع بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $links = app(HomepageRepositoryInterface::class)
            ->paginateQuickLinks(
                search: $this->search ?: null,
                status: $this->status ?: null,
            );

        return view('livewire.homepage.quick-links-index', [
            'links' => $links,
        ]);
    }
}
