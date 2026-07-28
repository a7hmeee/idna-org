<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\Enums\ProjectCategory;
use App\Domains\Projects\Enums\ProjectStatus;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicProjectsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $projectStatus = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedProjectStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(ProjectRepositoryInterface::class);

        $projects = $repo->getPublished(
            search: strlen($this->search) >= 2 ? $this->search : null,
            category: $this->category ?: null,
            projectStatus: $this->projectStatus ?: null,
        );

        $featured = $repo->getFeatured();

        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = \App\Domains\Municipality\Models\Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {
        }

        return view('livewire.projects.public-projects-index', [
            'projects' => $projects,
            'featured' => $featured,
            'categories' => ProjectCategory::cases(),
            'projectStatuses' => ProjectStatus::cases(),
            'municipalityName' => $municipalityName,
        ])->layout('layouts.home', [
            'title' => 'المشاريع | ' . $municipalityName,
            'metaDescription' => 'تصفح جميع مشاريع بلدية إذنا، وتعرف على نسب الإنجاز والتفاصيل.',
        ]);
    }
}
