<?php

declare(strict_types=1);

namespace App\Livewire\Tenders;

use App\Domains\Tenders\Actions\CreateTenderAction;
use App\Domains\Tenders\Actions\UpdateTenderAction;
use App\Domains\Tenders\DTOs\TenderData;
use App\Domains\Tenders\Enums\TenderStatus;
use App\Domains\Tenders\Models\Tender;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.dashboard')]
final class TenderForm extends Component
{
    use WithFileUploads;

    public ?int $tenderId = null;

    public string $tenderNumber = '';

    public string $titleAr = '';

    public string $titleEn = '';

    public string $summary = '';

    public string $description = '';

    public string $category = '';

    public string $issuingDepartment = '';

    public string $publicationDate = '';

    public string $submissionDeadline = '';

    public string $openingDate = '';

    public string $status = 'draft';

    public array $eligibilityRequirements = [''];

    public array $applicationInstructions = [''];

    public string $contactInfo = '';

    public string $contactPhone = '';

    public string $contactEmail = '';

    public array $tenderDocuments = [''];

    public array $resultDocuments = [];

    public string $budget = '';

    public string $budgetCurrency = 'ILS';

    public bool $isFeatured = false;

    public bool $isPublic = false;

    public $tenderDocumentUpload = null;

    public $resultDocumentUpload = null;

    public ?string $existingTenderDocumentPath = null;

    public ?string $existingResultDocumentPath = null;

    public function mount(?Tender $tender = null): void
    {
        if ($tender && $tender->exists) {
            $this->authorize('update', Tender::class);

            $this->tenderId = $tender->id;
            $this->tenderNumber = $tender->tender_number ?? '';
            $this->titleAr = $tender->title_ar;
            $this->titleEn = $tender->title_en ?? '';
            $this->summary = $tender->summary ?? '';
            $this->description = $tender->description ?? '';
            $this->category = $tender->category ?? '';
            $this->issuingDepartment = $tender->issuing_department ?? '';
            $this->publicationDate = $tender->publication_date?->format('Y-m-d') ?? '';
            $this->submissionDeadline = $tender->submission_deadline?->format('Y-m-d') ?? '';
            $this->openingDate = $tender->opening_date?->format('Y-m-d') ?? '';
            $this->status = $tender->status->value;
            $this->eligibilityRequirements = $tender->eligibility_requirements ?: [''];
            $this->applicationInstructions = $tender->application_instructions ?: [''];
            $this->contactInfo = $tender->contact_info ?? '';
            $this->contactPhone = $tender->contact_phone ?? '';
            $this->contactEmail = $tender->contact_email ?? '';
            $this->tenderDocuments = $tender->tender_documents ?: [''];
            $this->resultDocuments = $tender->result_documents ?? [];
            $this->budget = $tender->budget ?? '';
            $this->budgetCurrency = $tender->budget_currency ?? 'ILS';
            $this->isFeatured = $tender->is_featured;
            $this->isPublic = $tender->is_public;
        } else {
            $this->authorize('create', Tender::class);

            $this->publicationDate = now()->toDateString();
            $this->submissionDeadline = now()->addMonth()->toDateString();
        }
    }

    public function addEligibilityRequirement(): void
    {
        $this->eligibilityRequirements[] = '';
    }

    public function removeEligibilityRequirement(int $index): void
    {
        unset($this->eligibilityRequirements[$index]);
        $this->eligibilityRequirements = array_values($this->eligibilityRequirements);
    }

    public function addApplicationInstruction(): void
    {
        $this->applicationInstructions[] = '';
    }

    public function removeApplicationInstruction(int $index): void
    {
        unset($this->applicationInstructions[$index]);
        $this->applicationInstructions = array_values($this->applicationInstructions);
    }

    public function addTenderDocument(): void
    {
        $this->tenderDocuments[] = '';
    }

    public function removeTenderDocument(int $index): void
    {
        unset($this->tenderDocuments[$index]);
        $this->tenderDocuments = array_values($this->tenderDocuments);
    }

    public function addResultDocument(): void
    {
        $this->resultDocuments[] = '';
    }

    public function removeResultDocument(int $index): void
    {
        unset($this->resultDocuments[$index]);
        $this->resultDocuments = array_values($this->resultDocuments);
    }

    public function save(): void
    {
        $data = $this->validate([
            'tenderNumber' => ['nullable', 'string', 'max:100'],
            'titleAr' => ['required', 'string', 'max:255'],
            'titleEn' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'issuingDepartment' => ['required', 'string', 'max:255'],
            'publicationDate' => ['required', 'date'],
            'submissionDeadline' => ['required', 'date', 'after_or_equal:publicationDate'],
            'openingDate' => ['nullable', 'date', 'after_or_equal:publicationDate'],
            'status' => ['required', 'string', 'in:draft,open,closed,awarded,cancelled,archived'],
            'eligibilityRequirements' => ['nullable', 'array'],
            'eligibilityRequirements.*' => ['nullable', 'string', 'max:500'],
            'applicationInstructions' => ['nullable', 'array'],
            'applicationInstructions.*' => ['nullable', 'string', 'max:500'],
            'contactInfo' => ['nullable', 'string', 'max:500'],
            'contactPhone' => ['nullable', 'string', 'max:50'],
            'contactEmail' => ['nullable', 'email', 'max:255'],
            'tenderDocuments' => ['nullable', 'array'],
            'tenderDocuments.*' => ['nullable', 'string', 'max:500'],
            'resultDocuments' => ['nullable', 'array'],
            'resultDocuments.*' => ['nullable', 'string', 'max:500'],
            'budget' => ['nullable', 'string', 'max:100'],
            'budgetCurrency' => ['required', 'string', 'max:10'],
            'isFeatured' => ['boolean'],
            'isPublic' => ['boolean'],
        ]);

        $dto = TenderData::fromRequest([
            ...$data,
            'createdBy' => auth()->id(),
            'updatedBy' => auth()->id(),
        ]);

        if ($this->tenderId) {
            app(UpdateTenderAction::class)->execute($this->tenderId, $dto);
            session()->flash('success', 'تم تحديث المناقصة بنجاح.');
        } else {
            app(CreateTenderAction::class)->execute($dto);
            session()->flash('success', 'تم إضافة المناقصة بنجاح.');
        }

        $this->redirect(route('dashboard.tenders'), navigate: true);
    }

    public function render()
    {
        $statuses = TenderStatus::cases();

        return view('livewire.tenders.tender-form', compact(
            'statuses',
        ));
    }
}
