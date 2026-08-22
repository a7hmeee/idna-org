<?php

declare(strict_types=1);

namespace App\Livewire\Department;

use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\SharedKernel\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicDepartmentsPortal extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filter = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(DepartmentRepositoryInterface::class);
        $filterValue = $this->filter === 'featured' ? 'featured' : null;

        $departments = $repo->paginatePublicDepartments(
            search: strlen($this->search) >= 2 ? $this->search : null,
            filter: $filterValue,
            perPage: 12
        );

        $departmentIds = $departments->pluck('id');
        $serviceCounts = collect();
        if (class_exists(ElectronicService::class)) {
            $serviceCounts = ElectronicService::whereIn('department_id', $departmentIds)
                ->where('is_public', true)
                ->where('status', 'active')
                ->selectRaw('department_id, COUNT(*) as count')
                ->groupBy('department_id')
                ->pluck('count', 'department_id');
        }

        $carouselImages = [];
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
                $heroMedia = Media::where('mediable_type', $municipality->getMorphClass())
                    ->where('mediable_id', $municipality->getKey())
                    ->where('collection', 'departments-hero')
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->get();

                if ($heroMedia->isNotEmpty()) {
                    $carouselImages = $heroMedia->map(fn ($m) => asset('storage/'.$m->path))->toArray();
                } else {
                    $fallback = Media::where('mediable_type', $municipality->getMorphClass())
                        ->where('mediable_id', $municipality->getKey())
                        ->where('collection', 'images')
                        ->where('is_active', true)
                        ->orderBy('display_order')
                        ->first();
                    if ($fallback) {
                        $carouselImages[] = asset('storage/'.$fallback->path);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        return view('livewire.department.public-departments-portal', [
            'departments' => $departments,
            'serviceCounts' => $serviceCounts,
            'carouselImages' => $carouselImages,
            'municipalityName' => $municipalityName,
        ])->layout('layouts.home', [
            'title' => 'الدوائر والأقسام | '.$municipalityName,
            'metaDescription' => 'تصفح جميع الدوائر والأقسام في '.$municipalityName.'، وتعرف على خدماتها ومعلومات الاتصال الخاصة بها.',
        ]);
    }
}
