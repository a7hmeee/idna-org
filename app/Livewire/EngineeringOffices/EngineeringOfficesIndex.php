<?php

declare(strict_types=1);

namespace App\Livewire\EngineeringOffices;

use App\Domains\EngineeringOffices\Actions\ApproveEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Actions\DeleteEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Actions\SuspendEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Actions\ToggleEngineeringOfficePublicAction;
use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use App\Domains\EngineeringOffices\Enums\EngineeringOfficeApprovalStatus;
use App\Domains\EngineeringOffices\Enums\EngineeringOfficeStatus;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class EngineeringOfficesIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $approvalStatus = '';

    public string $status = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('view', EngineeringOffice::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingApprovalStatus(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    #[On('office-saved')]
    public function refresh(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('delete', EngineeringOffice::class);
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        $this->authorize('delete', EngineeringOffice::class);
        app(DeleteEngineeringOfficeAction::class)->execute($this->deletingId);

        session()->flash('success', 'تم حذف المكتب الهندسي بنجاح.');
        $this->closeDeleteModal();
    }

    public function approve(int $id): void
    {
        $this->authorize('approve', EngineeringOffice::class);
        app(ApproveEngineeringOfficeAction::class)->execute($id);
        session()->flash('success', 'تم اعتماد المكتب الهندسي بنجاح.');
    }

    public function suspend(int $id): void
    {
        $this->authorize('suspend', EngineeringOffice::class);
        app(SuspendEngineeringOfficeAction::class)->execute($id);
        session()->flash('success', 'تم إيقاف المكتب الهندسي بنجاح.');
    }

    public function togglePublic(int $id): void
    {
        $this->authorize('togglePublic', EngineeringOffice::class);
        app(ToggleEngineeringOfficePublicAction::class)->execute($id);
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $repository = app(EngineeringOfficeRepositoryInterface::class);
        $offices = $repository->paginateDashboard(
            $this->search ?: null,
            $this->approvalStatus ?: null,
            $this->status ?: null
        );

        return view('livewire.engineering-offices.engineering-offices-index', [
            'offices' => $offices,
            'approvalStatusOptions' => EngineeringOfficeApprovalStatus::options(),
            'statusOptions' => EngineeringOfficeStatus::options(),
            'canCreate' => auth()->user()->can('create', EngineeringOffice::class),
            'canUpdate' => auth()->user()->can('update', EngineeringOffice::class),
            'canDelete' => auth()->user()->can('delete', EngineeringOffice::class),
            'canApprove' => auth()->user()->can('approve', EngineeringOffice::class),
            'canSuspend' => auth()->user()->can('suspend', EngineeringOffice::class),
            'canTogglePublic' => auth()->user()->can('togglePublic', EngineeringOffice::class),
        ]);
    }
}
