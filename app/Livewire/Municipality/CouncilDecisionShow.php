<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\ArchiveCouncilDecisionAction;
use App\Domains\Municipality\Actions\CancelCouncilDecisionAction;
use App\Domains\Municipality\Actions\DeleteCouncilDecisionAction;
use App\Domains\Municipality\Actions\PublishCouncilDecisionAction;
use App\Domains\Municipality\Models\CouncilDecision;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class CouncilDecisionShow extends Component
{
    public CouncilDecision $councilDecision;

    public bool $showDeleteModal = false;

    public function mount(CouncilDecision $councilDecision): void
    {
        $this->authorize('view', CouncilDecision::class);

        $this->councilDecision = $councilDecision;
    }

    public function publish(PublishCouncilDecisionAction $action): void
    {
        $this->authorize('publish', CouncilDecision::class);

        $action->execute($this->councilDecision->id);

        $this->councilDecision = $this->councilDecision->fresh();

        session()->flash('success', 'تم نشر القرار بنجاح.');
    }

    public function archive(ArchiveCouncilDecisionAction $action): void
    {
        $this->authorize('archive', CouncilDecision::class);

        $action->execute($this->councilDecision->id);

        $this->councilDecision = $this->councilDecision->fresh();

        session()->flash('success', 'تم أرشفة القرار بنجاح.');
    }

    public function cancel(CancelCouncilDecisionAction $action): void
    {
        $this->authorize('cancel', CouncilDecision::class);

        $action->execute($this->councilDecision->id);

        $this->councilDecision = $this->councilDecision->fresh();

        session()->flash('success', 'تم إلغاء القرار بنجاح.');
    }

    public function confirmDelete(): void
    {
        $this->authorize('delete', CouncilDecision::class);

        $this->showDeleteModal = true;
    }

    public function delete(DeleteCouncilDecisionAction $action): void
    {
        $this->authorize('delete', CouncilDecision::class);

        $action->execute($this->councilDecision->id);

        session()->flash('success', 'تم حذف القرار بنجاح.');

        $this->redirect(route('dashboard.municipality.council-decisions'), navigate: true);
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.municipality.council-decision-show', [
            'canUpdate' => auth()->user()->can('update', CouncilDecision::class),
            'canDelete' => auth()->user()->can('delete', CouncilDecision::class),
            'canPublish' => auth()->user()->can('publish', CouncilDecision::class),
            'canArchive' => auth()->user()->can('archive', CouncilDecision::class),
            'canCancel' => auth()->user()->can('cancel', CouncilDecision::class),
        ]);
    }
}
