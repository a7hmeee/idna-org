<?php

declare(strict_types=1);

namespace App\Livewire\Jobs;

use App\Domains\Jobs\Models\EmploymentTypeLabel;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class EmploymentTypeLabelManager extends Component
{
    public ?int $editingId = null;

    public string $labelAr = '';

    public bool $isActive = true;

    public bool $showEditModal = false;

    public function mount(): void
    {
        if (! auth()->user()->can('jobs.update')) {
            abort(403);
        }
    }

    public function editLabel(EmploymentTypeLabel $label): void
    {
        if (! auth()->user()->can('jobs.update')) {
            abort(403);
        }

        $this->editingId = $label->id;
        $this->labelAr = $label->label_ar;
        $this->isActive = $label->is_active;
        $this->showEditModal = true;
    }

    public function saveLabel(): void
    {
        if (! auth()->user()->can('jobs.update')) {
            abort(403);
        }

        $this->validate([
            'labelAr' => ['required', 'string', 'max:255'],
        ]);

        EmploymentTypeLabel::where('id', $this->editingId)->update([
            'label_ar' => $this->labelAr,
            'is_active' => $this->isActive,
        ]);

        $this->showEditModal = false;
        $this->reset(['editingId', 'labelAr']);
        $this->isActive = true;

        session()->flash('success', 'تم تحديث تسمية نوع التوظيف بنجاح.');
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editingId', 'labelAr']);
        $this->isActive = true;
    }

    public function render()
    {
        $labels = EmploymentTypeLabel::orderBy('sort_order')->get();

        return view('livewire.jobs.employment-type-label-manager', [
            'labels' => $labels,
        ]);
    }
}
