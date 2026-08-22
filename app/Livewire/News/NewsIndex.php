<?php

declare(strict_types=1);

namespace App\Livewire\News;

use App\Domains\News\Actions\DeleteNewsAction;
use App\Domains\News\Actions\PublishNewsAction;
use App\Domains\News\Actions\ToggleFeaturedNewsAction;
use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\Models\NewsItem;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class NewsIndex extends Component
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
        $this->authorize('delete', NewsItem::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteNewsAction $action): void
    {
        $this->authorize('delete', NewsItem::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الخبر بنجاح.');
    }

    public function publish(int $id, PublishNewsAction $action): void
    {
        $this->authorize('publish', NewsItem::class);

        $action->execute($id);

        session()->flash('success', 'تم نشر الخبر بنجاح.');
    }

    public function unpublish(int $id, PublishNewsAction $action): void
    {
        $this->authorize('publish', NewsItem::class);

        $repo = app(NewsRepositoryInterface::class);
        $repo->unpublish($id);

        session()->flash('success', 'تم إلغاء نشر الخبر بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleFeaturedNewsAction $action): void
    {
        $this->authorize('feature', NewsItem::class);

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
        $news = app(NewsRepositoryInterface::class)->paginateDashboard();

        return view('livewire.news.news-index', [
            'news' => $news,
        ]);
    }
}
