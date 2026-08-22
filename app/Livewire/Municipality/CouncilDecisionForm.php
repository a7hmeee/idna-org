<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\Municipality\Actions\CreateCouncilDecisionAction;
use App\Domains\Municipality\Actions\UpdateCouncilDecisionAction;
use App\Domains\Municipality\DTOs\CouncilDecisionDTO;
use App\Domains\Municipality\Enums\CouncilDecisionStatus;
use App\Domains\Municipality\Enums\CouncilDecisionType;
use App\Domains\Municipality\Models\CouncilDecision;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class CouncilDecisionForm extends Component
{
    public ?int $editingId = null;

    public string $decision_number = '';

    public string $title = '';

    public ?string $summary = null;

    public ?string $content = null;

    public string $type = 'administrative';

    public string $status = 'draft';

    public ?string $decision_date = null;

    public ?string $session_number = null;

    public ?string $attachment_path = null;

    public bool $is_public = false;

    public int $sort_order = 0;

    public function mount(?CouncilDecision $councilDecision = null): void
    {
        if ($councilDecision && $councilDecision->exists) {
            $this->authorize('update', CouncilDecision::class);

            $this->editingId = $councilDecision->id;
            $this->decision_number = $councilDecision->decision_number;
            $this->title = $councilDecision->title;
            $this->summary = $councilDecision->summary;
            $this->content = $councilDecision->content;
            $this->type = $councilDecision->type;
            $this->status = $councilDecision->status;
            $this->decision_date = $councilDecision->decision_date?->format('Y-m-d');
            $this->session_number = $councilDecision->session_number;
            $this->attachment_path = $councilDecision->attachment_path;
            $this->is_public = $councilDecision->is_public;
            $this->sort_order = $councilDecision->sort_order;
        } else {
            $this->authorize('create', CouncilDecision::class);
        }
    }

    public function save(): void
    {
        $this->authorize($this->editingId ? 'update' : 'create', CouncilDecision::class);

        $validated = $this->validate([
            'decision_number' => ['required', 'string', 'max:255', Rule::unique('council_decisions', 'decision_number')->ignore($this->editingId)],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'type' => ['required', Rule::in(CouncilDecisionType::values())],
            'status' => ['required', Rule::in(CouncilDecisionStatus::values())],
            'decision_date' => ['nullable', 'date'],
            'session_number' => ['nullable', 'string', 'max:255'],
            'attachment_path' => ['nullable', 'string', 'max:500'],
            'is_public' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $dto = CouncilDecisionDTO::fromRequest($validated);

        if ($this->editingId) {
            app(UpdateCouncilDecisionAction::class)->execute($this->editingId, $dto);
        } else {
            app(CreateCouncilDecisionAction::class)->execute($dto);
        }

        session()->flash('success', $this->editingId ? 'تم تحديث القرار بنجاح.' : 'تم إنشاء القرار بنجاح.');

        $this->redirect(route('dashboard.municipality.council-decisions'), navigate: true);
    }

    public function render()
    {
        return view('livewire.municipality.council-decision-form', [
            'typeOptions' => CouncilDecisionType::options(),
            'statusOptions' => CouncilDecisionStatus::options(),
        ]);
    }
}
