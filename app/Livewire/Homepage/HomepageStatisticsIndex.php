<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Actions\DeleteHomepageStatisticAction;
use App\Domains\Homepage\Actions\ToggleHomepageStatisticAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class HomepageStatisticsIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->authorize('viewStatistics', HomepageSetting::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('deleteStatistic', HomepageSetting::class);

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(DeleteHomepageStatisticAction $action): void
    {
        $this->authorize('deleteStatistic', HomepageSetting::class);

        $action->execute($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف الإحصائية بنجاح.');
    }

    public function toggle(int $id, ToggleHomepageStatisticAction $action): void
    {
        $this->authorize('updateStatistic', HomepageSetting::class);

        $action->execute($id);

        session()->flash('success', 'تم تغيير حالة الإحصائية بنجاح.');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $statistics = app(HomepageRepositoryInterface::class)
            ->paginateStatistics(
                search: $this->search ?: null,
                status: $this->status ?: null,
            );

        return view('livewire.homepage.statistics-index', [
            'statistics' => $statistics,
        ]);
    }
}
