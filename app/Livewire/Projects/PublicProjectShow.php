<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Domains\Municipality\Models\Municipality;
use App\Domains\Projects\Actions\RecordProjectViewAction;
use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\Models\Project;
use Livewire\Component;

final class PublicProjectShow extends Component
{
    public ?Project $project = null;

    public function mount(?Project $project = null): void
    {
        if ($project && $project->exists) {
            abort_if(! $project->is_public || $project->status->value !== 'completed', 404);

            $this->project = $project;

            app(RecordProjectViewAction::class)->execute($project->id);
        }

        abort_unless($this->project, 404);
    }

    public function render()
    {
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {
        }

        $relatedProjects = app(ProjectRepositoryInterface::class)->getLatest(3)
            ->reject(fn ($p) => $p->id === $this->project->id)
            ->take(3);

        return view('livewire.projects.public-project-show', [
            'municipalityName' => $municipalityName,
            'relatedProjects' => $relatedProjects,
        ])->layout('layouts.home', [
            'title' => ($this->project->name_ar ?? 'المشروع').' | '.$municipalityName,
            'metaDescription' => $this->project->summary ?? 'تفاصيل مشروع '.($this->project->name_ar ?? '').' في '.$municipalityName,
        ]);
    }
}
