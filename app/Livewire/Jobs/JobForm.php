<?php

declare(strict_types=1);

namespace App\Livewire\Jobs;

use App\Domains\Department\Models\Department;
use App\Domains\Jobs\Actions\CreateJobAction;
use App\Domains\Jobs\Actions\UpdateJobAction;
use App\Domains\Jobs\DTOs\JobData;
use App\Domains\Jobs\Enums\ApplicationMethod;
use App\Domains\Jobs\Enums\EmploymentType;
use App\Domains\Jobs\Enums\JobStatus;
use App\Domains\Jobs\Models\Job;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class JobForm extends Component
{
    use WithFileUploads;

    public ?int $jobId = null;

    public string $title = '';

    public ?string $departmentId = null;

    public string $jobNumber = '';

    public string $employmentType = 'full_time';

    public string $location = '';

    public string $salary = '';

    public int $vacancies = 1;

    public string $summary = '';

    public string $description = '';

    public array $requirements = [''];

    public array $responsibilities = [''];

    public array $benefits = [];

    public array $requiredDocuments = [''];

    public string $applicationMethod = 'external_link';

    public string $applicationUrl = '';

    public string $applicationEmail = '';

    public string $applicationPhone = '';

    public $attachment = null;

    public ?string $existingAttachment = null;

    public string $publishAt = '';

    public string $closingAt = '';

    public string $status = 'draft';

    public bool $isPublic = false;

    public bool $isFeatured = false;

    public function mount(?Job $job = null): void
    {
        if ($job && $job->exists) {
            $this->authorize('update', Job::class);

            $this->jobId = $job->id;
            $this->title = $job->title;
            $this->departmentId = (string) ($job->department_id ?? '');
            $this->jobNumber = $job->job_number ?? '';
            $this->employmentType = $job->employment_type->value;
            $this->location = $job->location;
            $this->salary = $job->salary ?? '';
            $this->vacancies = $job->vacancies;
            $this->summary = $job->summary;
            $this->description = $job->description;
            $this->requirements = $job->requirements ?: [''];
            $this->responsibilities = $job->responsibilities ?: [''];
            $this->benefits = $job->benefits ?? [];
            $this->requiredDocuments = $job->required_documents ?: [''];
            $this->applicationMethod = $job->application_method->value;
            $this->applicationUrl = $job->application_url ?? '';
            $this->applicationEmail = $job->application_email ?? '';
            $this->applicationPhone = $job->application_phone ?? '';
            $this->existingAttachment = $job->attachment_path;
            $this->publishAt = $job->publish_at->format('Y-m-d');
            $this->closingAt = $job->closing_at->format('Y-m-d');
            $this->status = $job->status->value;
            $this->isPublic = $job->is_public;
            $this->isFeatured = $job->is_featured;
        } else {
            $this->authorize('create', Job::class);

            $this->publishAt = now()->toDateString();
            $this->closingAt = now()->addMonth()->toDateString();
        }
    }

    public function addRequirement(): void
    {
        $this->requirements[] = '';
    }

    public function removeRequirement(int $index): void
    {
        unset($this->requirements[$index]);
        $this->requirements = array_values($this->requirements);
    }

    public function addResponsibility(): void
    {
        $this->responsibilities[] = '';
    }

    public function removeResponsibility(int $index): void
    {
        unset($this->responsibilities[$index]);
        $this->responsibilities = array_values($this->responsibilities);
    }

    public function addBenefit(): void
    {
        $this->benefits[] = '';
    }

    public function removeBenefit(int $index): void
    {
        unset($this->benefits[$index]);
        $this->benefits = array_values($this->benefits);
    }

    public function addDocument(): void
    {
        $this->requiredDocuments[] = '';
    }

    public function removeDocument(int $index): void
    {
        unset($this->requiredDocuments[$index]);
        $this->requiredDocuments = array_values($this->requiredDocuments);
    }

    public function save(): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'departmentId' => ['nullable', 'string'],
            'jobNumber' => ['nullable', 'string', 'max:100'],
            'employmentType' => ['required', 'string', 'in:full_time,part_time,contract,temporary,volunteer,internship'],
            'location' => ['required', 'string', 'max:255'],
            'salary' => ['nullable', 'string', 'max:100'],
            'vacancies' => ['required', 'integer', 'min:1', 'max:999'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'array', 'min:1'],
            'requirements.*' => ['required', 'string', 'max:500'],
            'responsibilities' => ['required', 'array', 'min:1'],
            'responsibilities.*' => ['required', 'string', 'max:500'],
            'benefits' => ['nullable', 'array'],
            'benefits.*' => ['nullable', 'string', 'max:500'],
            'requiredDocuments' => ['required', 'array', 'min:1'],
            'requiredDocuments.*' => ['required', 'string', 'max:500'],
            'applicationMethod' => ['required', 'string', 'in:external_link,email,phone,office,download_form'],
            'applicationUrl' => ['nullable', 'string', 'max:500'],
            'applicationEmail' => ['nullable', 'string', 'max:255'],
            'applicationPhone' => ['nullable', 'string', 'max:50'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'publishAt' => ['required', 'date'],
            'closingAt' => ['required', 'date', 'after_or_equal:publishAt'],
            'status' => ['required', 'string', 'in:draft,published,closed,archived'],
            'isPublic' => ['boolean'],
            'isFeatured' => ['boolean'],
        ]);

        $attachmentPath = $this->existingAttachment;

        if ($this->attachment) {
            if ($this->existingAttachment) {
                Storage::disk('public')->delete($this->existingAttachment);
            }
            $attachmentPath = $this->attachment->store('jobs', 'public');
        }

        $dto = JobData::fromRequest([
            ...$data,
            'departmentId' => $this->departmentId ? (int) $this->departmentId : null,
            'attachmentPath' => $attachmentPath,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->jobId) {
            app(UpdateJobAction::class)->execute($this->jobId, $dto);
            session()->flash('success', 'تم تحديث الوظيفة بنجاح.');
        } else {
            app(CreateJobAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة الوظيفة بنجاح.');
        }

        $this->redirect(route('dashboard.jobs'), navigate: true);
    }

    public function render()
    {
        $departments = Department::orderBy('name')->get(['id', 'name']);
        $employmentTypes = EmploymentType::cases();
        $applicationMethods = ApplicationMethod::cases();
        $statuses = JobStatus::cases();

        return view('livewire.jobs.job-form', compact(
            'departments', 'employmentTypes', 'applicationMethods', 'statuses'
        ));
    }
}
