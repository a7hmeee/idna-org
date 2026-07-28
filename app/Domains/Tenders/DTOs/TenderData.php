<?php

declare(strict_types=1);

namespace App\Domains\Tenders\DTOs;

use App\Domains\Tenders\Enums\TenderStatus;

final readonly class TenderData
{
    public function __construct(
        public string $titleAr,
        public string $summary,
        public string $description,
        public string $issuingDepartment,
        public string $publicationDate,
        public string $submissionDeadline,
        public ?string $tenderNumber = null,
        public ?string $titleEn = null,
        public ?string $slug = null,
        public ?string $category = null,
        public ?string $openingDate = null,
        public ?string $status = null,
        public ?array $eligibilityRequirements = null,
        public ?array $applicationInstructions = null,
        public ?string $contactInfo = null,
        public ?string $contactPhone = null,
        public ?string $contactEmail = null,
        public ?array $tenderDocuments = null,
        public ?array $resultDocuments = null,
        public ?string $budget = null,
        public ?string $budgetCurrency = null,
        public ?bool $isFeatured = null,
        public ?bool $isPublic = null,
        public ?int $displayOrder = null,
        public ?int $viewsCount = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            titleAr: $validated['titleAr'],
            summary: $validated['summary'],
            description: $validated['description'],
            issuingDepartment: $validated['issuingDepartment'],
            publicationDate: $validated['publicationDate'],
            submissionDeadline: $validated['submissionDeadline'],
            tenderNumber: $validated['tenderNumber'] ?? null,
            titleEn: $validated['titleEn'] ?? null,
            slug: $validated['slug'] ?? null,
            category: $validated['category'] ?? null,
            openingDate: $validated['openingDate'] ?? null,
            status: $validated['status'] ?? null,
            eligibilityRequirements: $validated['eligibilityRequirements'] ?? null,
            applicationInstructions: $validated['applicationInstructions'] ?? null,
            contactInfo: $validated['contactInfo'] ?? null,
            contactPhone: $validated['contactPhone'] ?? null,
            contactEmail: $validated['contactEmail'] ?? null,
            tenderDocuments: $validated['tenderDocuments'] ?? null,
            resultDocuments: $validated['resultDocuments'] ?? null,
            budget: $validated['budget'] ?? null,
            budgetCurrency: $validated['budgetCurrency'] ?? null,
            isFeatured: isset($validated['isFeatured']) ? (bool) $validated['isFeatured'] : null,
            isPublic: isset($validated['isPublic']) ? (bool) $validated['isPublic'] : null,
            displayOrder: isset($validated['displayOrder']) ? (int) $validated['displayOrder'] : null,
            createdBy: isset($validated['createdBy']) ? (int) $validated['createdBy'] : null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'tender_number' => $this->tenderNumber,
            'title_ar' => $this->titleAr,
            'title_en' => $this->titleEn,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'category' => $this->category,
            'issuing_department' => $this->issuingDepartment,
            'publication_date' => $this->publicationDate,
            'submission_deadline' => $this->submissionDeadline,
            'opening_date' => $this->openingDate,
            'status' => $this->status,
            'eligibility_requirements' => $this->eligibilityRequirements,
            'application_instructions' => $this->applicationInstructions,
            'contact_info' => $this->contactInfo,
            'contact_phone' => $this->contactPhone,
            'contact_email' => $this->contactEmail,
            'tender_documents' => $this->tenderDocuments,
            'result_documents' => $this->resultDocuments,
            'budget' => $this->budget,
            'budget_currency' => $this->budgetCurrency,
            'is_featured' => $this->isFeatured,
            'is_public' => $this->isPublic,
            'display_order' => $this->displayOrder ?? 0,
            'views_count' => $this->viewsCount ?? 0,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
