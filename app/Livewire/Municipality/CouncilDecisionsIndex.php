<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\ArchiveCouncilDecisionAction;
use App\Domains\Municipality\Actions\CancelCouncilDecisionAction;
use App\Domains\Municipality\Actions\DeleteCouncilDecisionAction;
use App\Domains\Municipality\Actions\PublishCouncilDecisionAction;
use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilDecisionStatus;
use App\Domains\Municipality\Enums\CouncilDecisionType;
use App\Domains\Municipality\Models\CouncilDecision;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class CouncilDecisionsIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $type = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('view', CouncilDecision::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', CouncilDecision::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteCouncilDecisionAction $action): void
    {
        $this->authorize('delete', CouncilDecision::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف القرار بنجاح.');
    }

    public function publish(int $id, PublishCouncilDecisionAction $action): void
    {
        $this->authorize('publish', CouncilDecision::class);

        $action->execute($id);

        session()->flash('success', 'تم نشر القرار بنجاح.');
    }

    public function archive(int $id, ArchiveCouncilDecisionAction $action): void
    {
        $this->authorize('archive', CouncilDecision::class);

        $action->execute($id);

        session()->flash('success', 'تم أرشفة القرار بنجاح.');
    }

    public function cancel(int $id, CancelCouncilDecisionAction $action): void
    {
        $this->authorize('cancel', CouncilDecision::class);

        $action->execute($id);

        session()->flash('success', 'تم إلغاء القرار بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $decisions = app(CouncilDecisionRepositoryInterface::class)
            ->paginateForDashboard(
                search: $this->search ?: null,
                status: $this->status ?: null,
                type: $this->type ?: null,
                perPage: 15
            );

        return view('livewire.municipality.council-decisions-index', [
            'decisions' => $decisions,
            'statusOptions' => CouncilDecisionStatus::options(),
            'typeOptions' => CouncilDecisionType::options(),
            'canCreate' => auth()->user()->can('create', CouncilDecision::class),
            'canUpdate' => auth()->user()->can('update', CouncilDecision::class),
            'canDelete' => auth()->user()->can('delete', CouncilDecision::class),
            'canPublish' => auth()->user()->can('publish', CouncilDecision::class),
            'canArchive' => auth()->user()->can('archive', CouncilDecision::class),
            'canCancel' => auth()->user()->can('cancel', CouncilDecision::class),
        ]);
    }
}
