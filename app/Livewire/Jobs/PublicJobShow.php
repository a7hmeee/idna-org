<?php

declare(strict_types=1);

namespace App\Livewire\Jobs;

use App\Domains\Jobs\Actions\RecordJobViewAction;
use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use App\Domains\Jobs\Models\Job;
use Livewire\Component;

final class PublicJobShow extends Component
{
    public ?Job $job = null;

    public function mount(?Job $job = null): void
    {
        if ($job && $job->exists) {
            abort_if(!$job->is_public || $job->status->value !== 'published', 404);

            $this->job = $job;

            app(RecordJobViewAction::class)->execute($job->id);
        }
    }

    public function render()
    {
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = \App\Domains\Municipality\Models\Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {
        }

        $relatedJobs = app(JobRepositoryInterface::class)->getLatest(3)
            ->reject(fn ($j) => $j->id === $this->job->id)
            ->take(3);

        return view('livewire.jobs.public-job-show', [
            'municipalityName' => $municipalityName,
            'relatedJobs' => $relatedJobs,
        ])->layout('layouts.home', [
            'title' => ($this->job->title ?? 'الوظيفة') . ' | ' . $municipalityName,
            'metaDescription' => $this->job->summary ?? 'التقديم على وظيفة ' . ($this->job->title ?? '') . ' في ' . $municipalityName,
        ]);
    }
}
