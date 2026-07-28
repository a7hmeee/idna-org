<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\DeleteCouncilMemberAction;
use App\Domains\Municipality\Actions\ToggleFeaturedCouncilMemberAction;
use App\Domains\Municipality\Actions\TogglePublicCouncilMemberAction;
use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Enums\CouncilMemberStatus;
use App\Domains\Municipality\Models\CouncilMember;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class CouncilMembersIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $position = '';
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('view', CouncilMember::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPosition(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', CouncilMember::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteCouncilMemberAction $action): void
    {
        $this->authorize('delete', CouncilMember::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف العضو بنجاح.');
    }

    public function togglePublic(int $id, TogglePublicCouncilMemberAction $action): void
    {
        $this->authorize('togglePublic', CouncilMember::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة الظهور للعامة بنجاح.');
    }

    public function toggleFeatured(int $id, ToggleFeaturedCouncilMemberAction $action): void
    {
        $this->authorize('toggleFeatured', CouncilMember::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة المميز بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $members = app(CouncilMemberRepositoryInterface::class)
            ->paginateDashboard(
                search: $this->search ?: null,
                status: $this->status ?: null,
                position: $this->position ?: null,
                perPage: 15
            );

        return view('livewire.municipality.council-members-index', [
            'members' => $members,
            'statusOptions' => CouncilMemberStatus::options(),
            'positionOptions' => CouncilMemberPosition::options(),
            'canCreate' => auth()->user()->can('create', CouncilMember::class),
            'canUpdate' => auth()->user()->can('update', CouncilMember::class),
            'canDelete' => auth()->user()->can('delete', CouncilMember::class),
            'canTogglePublic' => auth()->user()->can('togglePublic', CouncilMember::class),
            'canToggleFeatured' => auth()->user()->can('toggleFeatured', CouncilMember::class),
        ]);
    }
}
