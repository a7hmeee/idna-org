<?php

declare(strict_types=1);

namespace App\Livewire\Jobs;

use App\Domains\Jobs\Actions\ArchiveJobAction;
use App\Domains\Jobs\Actions\CloseJobAction;
use App\Domains\Jobs\Actions\DeleteJobAction;
use App\Domains\Jobs\Actions\PublishJobAction;
use App\Domains\Jobs\Actions\ToggleFeaturedJobAction;
use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use App\Domains\Jobs\Models\Job;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class JobsIndex extends Component
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
        $this->authorize('delete', Job::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteJobAction $action): void
    {
        $this->authorize('delete', Job::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الوظيفة بنجاح.');
    }

    public function publish(int $id, PublishJobAction $action): void
    {
        $this->authorize('publish', Job::class);

        $action->execute($id);

        session()->flash('success', 'تم نشر الوظيفة بنجاح.');
    }

    public function close(int $id, CloseJobAction $action): void
    {
        $this->authorize('update', Job::class);

        $action->execute($id);

        session()->flash('success', 'تم إغلاق الوظيفة بنجاح.');
    }

    public function archive(int $id, ArchiveJobAction $action): void
    {
        $this->authorize('archive', Job::class);

        $action->execute($id);

        session()->flash('success', 'تم أرشفة الوظيفة بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleFeaturedJobAction $action): void
    {
        $this->authorize('update', Job::class);

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
        $jobs = app(JobRepositoryInterface::class)->paginateDashboard();

        return view('livewire.jobs.jobs-index', [
            'jobs' => $jobs,
        ]);
    }
}
