<?php

declare(strict_types=1);

namespace App\Livewire\Audit;

use App\Domains\Audit\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class AuditIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $action = '';

    public string $module = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public bool $showDetailModal = false;

    public ?array $selectedLog = null;

    public function boot(): void
    {
        if (! auth()->user()->can('audit.view')) {
            abort(403);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedAction(): void
    {
        $this->resetPage();
    }

    public function updatedModule(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function getAuditLogs(): LengthAwarePaginator
    {
        $query = AuditLog::query()->latestFirst();

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('user_email', 'like', "%{$search}%");
            });
        }

        if ($this->action) {
            $query->forAction($this->action);
        }

        if ($this->module) {
            $query->forModule($this->module);
        }

        if ($this->dateFrom && $this->dateTo) {
            $query->dateRange($this->dateFrom, $this->dateTo);
        } elseif ($this->dateFrom) {
            $query->where('created_at', '>=', $this->dateFrom);
        } elseif ($this->dateTo) {
            $query->where('created_at', '<=', $this->dateTo.' 23:59:59');
        }

        return $query->paginate(20);
    }

    public function getUniqueActions(): array
    {
        return AuditLog::distinct()->pluck('action')->filter()->values()->toArray();
    }

    public function getUniqueModules(): array
    {
        return AuditLog::distinct()->pluck('module')->filter()->values()->toArray();
    }

    public function viewDetail(int $id): void
    {
        $log = AuditLog::find($id);

        if ($log) {
            $this->selectedLog = $log->toArray();
            $this->showDetailModal = true;
        }
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedLog = null;
    }

    public function render()
    {
        return view('livewire.audit.index', [
            'auditLogs' => $this->getAuditLogs(),
            'actions' => $this->getUniqueActions(),
            'modules' => $this->getUniqueModules(),
        ]);
    }
}
