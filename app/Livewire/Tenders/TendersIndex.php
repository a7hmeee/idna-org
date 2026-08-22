<?php

declare(strict_types=1);

namespace App\Livewire\Tenders;

use App\Domains\Tenders\Actions\ArchiveTenderAction;
use App\Domains\Tenders\Actions\CancelTenderAction;
use App\Domains\Tenders\Actions\DeleteTenderAction;
use App\Domains\Tenders\Actions\PublishTenderAction;
use App\Domains\Tenders\Actions\ToggleFeaturedTenderAction;
use App\Domains\Tenders\Contracts\TenderRepositoryInterface;
use App\Domains\Tenders\Models\Tender;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class TendersIndex extends Component
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
        $this->authorize('delete', Tender::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteTenderAction $action): void
    {
        $this->authorize('delete', Tender::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف المناقصة بنجاح.');
    }

    public function publish(int $id, PublishTenderAction $action): void
    {
        $this->authorize('publish', Tender::class);

        $action->execute($id);

        session()->flash('success', 'تم نشر المناقصة بنجاح.');
    }

    public function cancel(int $id, CancelTenderAction $action): void
    {
        $this->authorize('update', Tender::class);

        $action->execute($id);

        session()->flash('success', 'تم إلغاء المناقصة بنجاح.');
    }

    public function archive(int $id, ArchiveTenderAction $action): void
    {
        $this->authorize('archive', Tender::class);

        $action->execute($id);

        session()->flash('success', 'تم أرشفة المناقصة بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleFeaturedTenderAction $action): void
    {
        $this->authorize('update', Tender::class);

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
        $tenders = app(TenderRepositoryInterface::class)->paginateDashboard();

        return view('livewire.tenders.tenders-index', [
            'tenders' => $tenders,
        ]);
    }
}
