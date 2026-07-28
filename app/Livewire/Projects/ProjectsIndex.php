<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Domains\Projects\Actions\DeleteProjectAction;
use App\Domains\Projects\Actions\PublishProjectAction;
use App\Domains\Projects\Actions\ToggleFeaturedProjectAction;
use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class ProjectsIndex extends Component
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
        $this->authorize('delete', Project::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteProjectAction $action): void
    {
        $this->authorize('delete', Project::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف المشروع بنجاح.');
    }

    public function publish(int $id, PublishProjectAction $action): void
    {
        $this->authorize('publish', Project::class);

        $action->execute($id);

        session()->flash('success', 'تم نشر المشروع بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleFeaturedProjectAction $action): void
    {
        $this->authorize('feature', Project::class);

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
        $projects = app(ProjectRepositoryInterface::class)->paginateDashboard();

        return view('livewire.projects.projects-index', [
            'projects' => $projects,
        ]);
    }
}
