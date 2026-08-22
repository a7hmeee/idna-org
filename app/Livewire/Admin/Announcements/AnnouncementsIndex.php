<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Announcements;

use App\Domains\Announcements\Actions\DeleteAnnouncementAction;
use App\Domains\Announcements\Actions\PublishAnnouncementAction;
use App\Domains\Announcements\Actions\ToggleFeaturedAnnouncementAction;
use App\Domains\Announcements\Actions\UnpublishAnnouncementAction;
use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Announcements\Models\Announcement;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class AnnouncementsIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

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
        $this->authorize('delete', Announcement::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteAnnouncementAction $action): void
    {
        $this->authorize('delete', Announcement::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الإعلان بنجاح.');
    }

    public function publish(int $id, PublishAnnouncementAction $action): void
    {
        $this->authorize('publish', Announcement::class);

        $action->execute($id);

        session()->flash('success', 'تم نشر الإعلان بنجاح.');
    }

    public function unpublish(int $id, UnpublishAnnouncementAction $action): void
    {
        $this->authorize('publish', Announcement::class);

        $action->execute($id);

        session()->flash('success', 'تم إلغاء نشر الإعلان بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleFeaturedAnnouncementAction $action): void
    {
        $this->authorize('update', Announcement::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة التميز بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $announcements = app(AnnouncementRepositoryInterface::class)->paginateDashboard();

        return view('livewire.admin.announcements.announcements-index', [
            'announcements' => $announcements,
        ]);
    }
}
