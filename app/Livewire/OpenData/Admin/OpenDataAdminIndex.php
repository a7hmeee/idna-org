<?php

declare(strict_types=1);

namespace App\Livewire\OpenData\Admin;

use App\Domains\OpenData\Models\OpenDataset;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class OpenDataAdminIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    public function delete(OpenDataset $dataset): void
    {
        $this->authorize('delete', OpenDataset::class);

        app(\App\Domains\OpenData\Actions\DeleteOpenDatasetAction::class)->execute($dataset);

        session()->flash('success', 'تم حذف مجموعة البيانات بنجاح');
    }

    public function render()
    {
        $query = OpenDataset::query()->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q): void {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('category', 'like', "%{$this->search}%");
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $datasets = $query->paginate(15);

        return view('livewire.open-data.admin.index', [
            'datasets' => $datasets,
        ]);
    }
}
