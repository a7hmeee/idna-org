<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\DeleteCouncilMemberAction;
use App\Domains\Municipality\Actions\ToggleFeaturedCouncilMemberAction;
use App\Domains\Municipality\Actions\TogglePublicCouncilMemberAction;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Enums\CouncilMemberStatus;
use App\Domains\Municipality\Models\CouncilMember;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class CouncilMemberProfile extends Component
{
    public CouncilMember $councilMember;

    public bool $showDeleteModal = false;

    public function mount(CouncilMember $councilMember): void
    {
        $this->authorize('view', CouncilMember::class);

        $this->councilMember = $councilMember;
    }

    public function togglePublic(TogglePublicCouncilMemberAction $action): void
    {
        $this->authorize('togglePublic', CouncilMember::class);

        $action->execute($this->councilMember->id);

        $this->councilMember = $this->councilMember->fresh();

        session()->flash('success', 'تم تغيير حالة الظهور للعامة بنجاح.');
    }

    public function toggleFeatured(ToggleFeaturedCouncilMemberAction $action): void
    {
        $this->authorize('toggleFeatured', CouncilMember::class);

        $action->execute($this->councilMember->id);

        $this->councilMember = $this->councilMember->fresh();

        session()->flash('success', 'تم تغيير حالة المميز بنجاح.');
    }

    public function confirmDelete(): void
    {
        $this->authorize('delete', CouncilMember::class);

        $this->showDeleteModal = true;
    }

    public function delete(DeleteCouncilMemberAction $action): void
    {
        $this->authorize('delete', CouncilMember::class);

        $action->execute($this->councilMember->id);

        session()->flash('success', 'تم حذف العضو بنجاح.');

        $this->redirect(route('dashboard.municipality.council-members'), navigate: true);
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.municipality.council-member-profile', [
            'positionLabel' => CouncilMemberPosition::tryFrom($this->councilMember->position)?->label() ?? $this->councilMember->position,
            'statusLabel' => CouncilMemberStatus::tryFrom($this->councilMember->status)?->label() ?? $this->councilMember->status,
            'canUpdate' => auth()->user()->can('update', CouncilMember::class),
            'canDelete' => auth()->user()->can('delete', CouncilMember::class),
            'canTogglePublic' => auth()->user()->can('togglePublic', CouncilMember::class),
            'canToggleFeatured' => auth()->user()->can('toggleFeatured', CouncilMember::class),
        ]);
    }
}
