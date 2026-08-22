<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Domains\Department\Models\Department;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Component;

final class PublicDepartmentShow extends Component
{
    public Department $department;

    public function mount(string $department): void
    {
        $dept = Department::where('slug', $department)->where('is_public', true)->first();
        abort_unless($dept, 404);

        $this->department = $dept->loadMissing('creator', 'updater');
    }

    public function render()
    {
        $services = ElectronicService::with('category:id,name,slug')
            ->where('department_id', $this->department->id)
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        return view('livewire.department.public-department-show', [
            'services' => $services,
            'municipalityName' => $municipalityName,
        ])->layout('layouts.home', [
            'title' => $this->department->name.' | '.$municipalityName,
            'metaDescription' => $this->department->short_description ?? 'تعرف على قسم '.$this->department->name.' وخدماته في '.$municipalityName,
        ]);
    }
}
