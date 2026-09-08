<?php

declare(strict_types=1);

namespace App\Livewire\Authentication;

use App\Domains\Authentication\Models\LoginActivity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class LoginActivityIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $eventType = '';

    public string $successFilter = '';

    public function boot(): void
    {
        if (! auth()->user()->can('login_activity.view')) {
            abort(403);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedEventType(): void
    {
        $this->resetPage();
    }

    public function updatedSuccessFilter(): void
    {
        $this->resetPage();
    }

    public function getActivities(): LengthAwarePaginator
    {
        $query = LoginActivity::with('user');

        if ($this->search) {
            $query->where(function ($q): void {
                $q->where('ip_address', 'like', "%{$this->search}%")
                    ->orWhere('user_agent', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"));
            });
        }

        if ($this->eventType) {
            $query->where('event_type', $this->eventType);
        }

        if ($this->successFilter !== '') {
            $query->where('successful', $this->successFilter === '1');
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function render()
    {
        return view('livewire.authentication.login-activity-index', [
            'activities' => $this->getActivities(),
        ]);
    }
}
