<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Domains\Projects\Actions\CreateProjectAction;
use App\Domains\Projects\Actions\UpdateProjectAction;
use App\Domains\Projects\DTOs\ProjectData;
use App\Domains\Projects\Enums\ProjectCategory;
use App\Domains\Projects\Enums\ProjectStatus;
use App\Domains\Projects\Models\Project;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class ProjectForm extends Component
{
    use WithFileUploads;

    public ?int $projectId = null;

    public string $nameAr = '';

    public string $nameEn = '';

    public string $category = 'infrastructure';

    public string $projectStatus = 'planned';

    public string $summary = '';

    public string $description = '';

    public string $startDate = '';

    public string $expectedCompletionDate = '';

    public string $actualCompletionDate = '';

    public string $location = '';

    public ?string $budget = null;

    public string $budgetCurrency = 'ILS';

    public int $implementationPercentage = 0;

    public string $contractor = '';

    public string $fundingEntity = '';

    public $coverImage = null;

    public ?string $existingCoverImage = null;

    public array $gallery = [];

    public array $existingGallery = [];

    public array $documents = [];

    public bool $isFeatured = false;

    public bool $isPublic = false;

    public string $status = 'planned';

    protected function rules(): array
    {
        return [
            'nameAr' => ['required', 'string', 'max:255'],
            'nameEn' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:'.implode(',', array_map(fn ($c) => $c->value, ProjectCategory::cases()))],
            'projectStatus' => ['required', 'string', 'in:'.implode(',', array_map(fn ($s) => $s->value, ProjectStatus::cases()))],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'startDate' => ['nullable', 'date'],
            'expectedCompletionDate' => ['nullable', 'date', 'after_or_equal:startDate'],
            'actualCompletionDate' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'budgetCurrency' => ['required', 'string', 'max:3'],
            'implementationPercentage' => ['required', 'integer', 'min:0', 'max:100'],
            'contractor' => ['nullable', 'string', 'max:255'],
            'fundingEntity' => ['nullable', 'string', 'max:255'],
            'coverImage' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'isFeatured' => ['boolean'],
            'isPublic' => ['boolean'],
            'status' => ['required', 'string', 'in:'.implode(',', array_map(fn ($s) => $s->value, ProjectStatus::cases()))],
        ];
    }

    protected $messages = [
        'nameAr.required' => 'اسم المشروع مطلوب.',
        'category.required' => 'التصنيف مطلوب.',
        'budget.numeric' => 'الميزانية يجب أن تكون رقماً.',
        'implementationPercentage.required' => 'نسبة الإنجاز مطلوبة.',
        'implementationPercentage.max' => 'نسبة الإنجاز يجب ألا تتجاوز 100%.',
        'coverImage.max' => 'يجب ألا تتجاوز الصورة 5 ميجابايت.',
        'gallery.*.max' => 'يجب ألا يتجاوز كل ملف 5 ميجابايت.',
    ];

    public function mount(?Project $project = null): void
    {
        if ($project && $project->exists) {
            $this->authorize('update', Project::class);

            $this->projectId = $project->id;
            $this->nameAr = $project->name_ar;
            $this->nameEn = $project->name_en ?? '';
            $this->category = $project->category->value;
            $this->projectStatus = $project->project_status->value;
            $this->summary = $project->summary ?? '';
            $this->description = $project->description ?? '';
            $this->startDate = $project->start_date?->format('Y-m-d') ?? '';
            $this->expectedCompletionDate = $project->expected_completion_date?->format('Y-m-d') ?? '';
            $this->actualCompletionDate = $project->actual_completion_date?->format('Y-m-d') ?? '';
            $this->location = $project->location ?? '';
            $this->budget = $project->budget ? (string) $project->budget : null;
            $this->budgetCurrency = $project->budget_currency;
            $this->implementationPercentage = $project->implementation_percentage;
            $this->contractor = $project->contractor ?? '';
            $this->fundingEntity = $project->funding_entity ?? '';
            $this->existingCoverImage = $project->cover_image_path;
            $this->existingGallery = $project->gallery ?? [];
            $this->isFeatured = $project->is_featured;
            $this->isPublic = $project->is_public;
            $this->status = $project->status->value;
        } else {
            $this->authorize('create', Project::class);

            $this->startDate = now()->toDateString();
        }
    }

    public function addGalleryImage(): void
    {
        $this->gallery[] = '';
    }

    public function removeGalleryImage(int $index): void
    {
        unset($this->gallery[$index]);
        $this->gallery = array_values($this->gallery);
    }

    public function removeExistingGalleryImage(int $index): void
    {
        if (isset($this->existingGallery[$index])) {
            Storage::disk('public')->delete($this->existingGallery[$index]);
        }
        unset($this->existingGallery[$index]);
        $this->existingGallery = array_values($this->existingGallery);
    }

    public function addDocument(): void
    {
        $this->documents[] = '';
    }

    public function removeDocument(int $index): void
    {
        unset($this->documents[$index]);
        $this->documents = array_values($this->documents);
    }

    public function save(): void
    {
        $data = $this->validate();

        $coverImagePath = $this->existingCoverImage;

        if ($this->coverImage) {
            if ($this->existingCoverImage) {
                Storage::disk('public')->delete($this->existingCoverImage);
            }
            $coverImagePath = $this->coverImage->store('projects/cover', 'public');
        }

        $allGallery = $this->existingGallery;

        foreach ($this->gallery as $index => $file) {
            if ($file && is_object($file)) {
                $path = $file->store('projects/gallery', 'public');
                $allGallery[] = $path;
            }
        }

        $dto = ProjectData::fromRequest([
            'nameAr' => $this->nameAr,
            'nameEn' => $this->nameEn ?: null,
            'category' => ProjectCategory::from($this->category),
            'projectStatus' => ProjectStatus::from($this->projectStatus),
            'status' => ProjectStatus::from($this->status),
            'summary' => $this->summary ?: null,
            'description' => $this->description ?: null,
            'startDate' => $this->startDate ?: null,
            'expectedCompletionDate' => $this->expectedCompletionDate ?: null,
            'actualCompletionDate' => $this->actualCompletionDate ?: null,
            'location' => $this->location ?: null,
            'budget' => $this->budget ? (float) $this->budget : null,
            'budgetCurrency' => $this->budgetCurrency,
            'implementationPercentage' => $this->implementationPercentage,
            'contractor' => $this->contractor ?: null,
            'fundingEntity' => $this->fundingEntity ?: null,
            'coverImagePath' => $coverImagePath,
            'gallery' => ! empty($allGallery) ? $allGallery : null,
            'isFeatured' => $this->isFeatured,
            'isPublic' => $this->isPublic,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->projectId) {
            app(UpdateProjectAction::class)->execute($this->projectId, $dto);
            session()->flash('success', 'تم تحديث المشروع بنجاح.');
        } else {
            app(CreateProjectAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة المشروع بنجاح.');
        }

        $this->redirect(route('dashboard.projects'), navigate: true);
    }

    public function removeCoverImage(): void
    {
        if ($this->existingCoverImage) {
            Storage::disk('public')->delete($this->existingCoverImage);
        }
        $this->existingCoverImage = null;
        $this->coverImage = null;
    }

    public function render()
    {
        $categories = ProjectCategory::cases();
        $projectStatuses = ProjectStatus::cases();
        $statuses = ProjectStatus::cases();

        return view('livewire.projects.project-form', compact(
            'categories', 'projectStatuses', 'statuses'
        ));
    }
}
