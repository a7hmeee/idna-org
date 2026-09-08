<?php

declare(strict_types=1);

namespace App\Livewire\ContactRequests;

use App\Domains\Authentication\Models\User;
use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use App\Domains\ContactRequests\Enums\ContactRequestStatus;
use App\Domains\ContactRequests\Models\ContactRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ContactRequestShow extends Component
{
    public ?ContactRequest $request = null;

    public string $status = '';

    public string $internalNotes = '';

    public string $responseNotes = '';

    public ?int $assignedTo = null;

    public bool $showStatusModal = false;

    public bool $showNotesModal = false;

    public bool $showAssignModal = false;

    public function mount(int $id): void
    {
        if (! auth()->user()->can('contact_requests.view')) {
            abort(403);
        }

        $repo = app(ContactRequestRepositoryInterface::class);
        $this->request = $repo->findById($id);

        if (! $this->request) {
            abort(404);
        }

        $this->status = $this->request->status->value;
        $this->internalNotes = $this->request->internal_notes ?? '';
        $this->responseNotes = $this->request->response_notes ?? '';
        $this->assignedTo = $this->request->assigned_to ? (int) $this->request->assigned_to : null;
    }

    public function openStatusModal(): void
    {
        $this->showStatusModal = true;
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
    }

    public function updateStatus(): void
    {
        if (! auth()->user()->can('contact_requests.resolve')) {
            abort(403);
        }

        $this->validate([
            'status' => ['required', 'string', 'in:pending,in_progress,resolved,closed'],
        ]);

        $data = ['status' => ContactRequestStatus::from($this->status)];

        if ($this->status === 'in_progress') {
            $data['in_progress_at'] = now();
        } elseif ($this->status === 'resolved') {
            $data['resolved_at'] = now();
        }

        ContactRequest::where('id', $this->request->id)->update($data);

        $this->request->refresh();
        $this->showStatusModal = false;

        session()->flash('success', 'تم تحديث الحالة بنجاح.');
    }

    public function openNotesModal(): void
    {
        $this->showNotesModal = true;
    }

    public function closeNotesModal(): void
    {
        $this->showNotesModal = false;
    }

    public function saveNotes(): void
    {
        if (! auth()->user()->can('contact_requests.resolve')) {
            abort(403);
        }

        ContactRequest::where('id', $this->request->id)->update([
            'internal_notes' => $this->internalNotes ?: null,
            'response_notes' => $this->responseNotes ?: null,
        ]);

        $this->request->refresh();
        $this->showNotesModal = false;

        session()->flash('success', 'تم حفظ الملاحظات بنجاح.');
    }

    public function openAssignModal(): void
    {
        $this->showAssignModal = true;
    }

    public function closeAssignModal(): void
    {
        $this->showAssignModal = false;
    }

    public function assignTo(): void
    {
        if (! auth()->user()->can('contact_requests.resolve')) {
            abort(403);
        }

        ContactRequest::where('id', $this->request->id)->update([
            'assigned_to' => $this->assignedTo ? (string) $this->assignedTo : null,
        ]);

        $this->request->refresh();
        $this->showAssignModal = false;

        session()->flash('success', 'تم تعيين الموظف بنجاح.');
    }

    public function render()
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        $statuses = ContactRequestStatus::cases();

        return view('livewire.contact-requests.show', [
            'users' => $users,
            'statuses' => $statuses,
        ]);
    }
}
