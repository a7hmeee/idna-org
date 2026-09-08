<?php

declare(strict_types=1);

namespace App\Livewire\WaterSchedule;

use App\Domains\WaterSchedule\Models\WaterStatusLabel;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class WaterStatusLabelManager extends Component
{
    public ?int $editingId = null;

    public string $labelAr = '';

    public string $color = '';

    public string $bgColor = '';

    public string $dotColor = '';

    public bool $isActive = true;

    public bool $showEditModal = false;

    public function mount(): void
    {
        if (! auth()->user()->can('water.update')) {
            abort(403);
        }
    }

    public function editLabel(WaterStatusLabel $label): void
    {
        if (! auth()->user()->can('water.update')) {
            abort(403);
        }

        $this->editingId = $label->id;
        $this->labelAr = $label->label_ar;
        $this->color = $label->color;
        $this->bgColor = $label->bg_color;
        $this->dotColor = $label->dot_color;
        $this->isActive = $label->is_active;
        $this->showEditModal = true;
    }

    public function saveLabel(): void
    {
        if (! auth()->user()->can('water.update')) {
            abort(403);
        }

        $this->validate([
            'labelAr' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:20'],
            'bgColor' => ['required', 'string', 'max:20'],
            'dotColor' => ['required', 'string', 'max:20'],
        ]);

        WaterStatusLabel::where('id', $this->editingId)->update([
            'label_ar' => $this->labelAr,
            'color' => $this->color,
            'bg_color' => $this->bgColor,
            'dot_color' => $this->dotColor,
            'is_active' => $this->isActive,
        ]);

        $this->showEditModal = false;
        $this->reset(['editingId', 'labelAr', 'color', 'bgColor', 'dotColor']);
        $this->isActive = true;

        session()->flash('success', 'تم تحديث حالة المياه بنجاح.');
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editingId', 'labelAr', 'color', 'bgColor', 'dotColor']);
        $this->isActive = true;
    }

    public function render()
    {
        $labels = WaterStatusLabel::orderBy('sort_order')->get();

        return view('livewire.water-schedule.status-label-manager', [
            'labels' => $labels,
        ]);
    }
}
