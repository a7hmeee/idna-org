<?php

declare(strict_types=1);

namespace App\Livewire\ContactRequests;

use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class ContactRequestsIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public bool $showDetailModal = false;

    public ?array $selectedRequest = null;

    public function boot(): void
    {
        if (! auth()->user()->can('contact_requests.view')) {
            abort(403);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function viewDetail(int $id): void
    {
        if (! auth()->user()->can('contact_requests.view')) {
            abort(403);
        }

        $repo = app(ContactRequestRepositoryInterface::class);
        $request = $repo->findById($id);

        if ($request) {
            $this->selectedRequest = $request->toArray();
            $this->showDetailModal = true;
        }
    }

    public function markResolved(int $id): void
    {
        if (! auth()->user()->can('contact_requests.resolve')) {
            abort(403);
        }

        $repo = app(ContactRequestRepositoryInterface::class);
        $repo->markResolved($id);

        $this->showDetailModal = false;
        $this->selectedRequest = null;

        session()->flash('success', 'تم تحديد الطلب كمحلول.');
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedRequest = null;
    }

    public function render()
    {
        $requests = app(ContactRequestRepositoryInterface::class)->paginateDashboard(
            search: $this->search ?: null,
            status: $this->status ?: null,
        );

        return view('livewire.contact-requests.index', [
            'requests' => $requests,
        ]);
    }
}
