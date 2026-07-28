<?php

declare(strict_types=1);

namespace App\Domains\Homepage\DTOs;

final readonly class HomepageSettingData
{
    public function __construct(
        public ?string $siteTitle = null,
        public ?string $siteSubtitle = null,
        public ?string $portalUrl = null,
        public ?string $primaryButtonText = null,
        public ?string $secondaryButtonText = null,
        public ?string $secondaryButtonUrl = null,
        public ?string $welcomeTitle = null,
        public ?string $welcomeDescription = null,
        public ?string $mayorMessageTitle = null,
        public ?string $mayorMessage = null,
        public ?string $mayorImagePath = null,
        public ?bool $showMayorMessage = null,
        public ?string $contactCtaTitle = null,
        public ?string $contactCtaDescription = null,
        public ?string $contactCtaButtonText = null,
        public ?string $contactCtaButtonUrl = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            siteTitle: $validated['siteTitle'] ?? null,
            siteSubtitle: $validated['siteSubtitle'] ?? null,
            portalUrl: $validated['portalUrl'] ?? null,
            primaryButtonText: $validated['primaryButtonText'] ?? null,
            secondaryButtonText: $validated['secondaryButtonText'] ?? null,
            secondaryButtonUrl: $validated['secondaryButtonUrl'] ?? null,
            welcomeTitle: $validated['welcomeTitle'] ?? null,
            welcomeDescription: $validated['welcomeDescription'] ?? null,
            mayorMessageTitle: $validated['mayorMessageTitle'] ?? null,
            mayorMessage: $validated['mayorMessage'] ?? null,
            mayorImagePath: $validated['mayorImagePath'] ?? null,
            showMayorMessage: isset($validated['showMayorMessage']) ? (bool) $validated['showMayorMessage'] : null,
            contactCtaTitle: $validated['contactCtaTitle'] ?? null,
            contactCtaDescription: $validated['contactCtaDescription'] ?? null,
            contactCtaButtonText: $validated['contactCtaButtonText'] ?? null,
            contactCtaButtonUrl: $validated['contactCtaButtonUrl'] ?? null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'site_title' => $this->siteTitle,
            'site_subtitle' => $this->siteSubtitle,
            'portal_url' => $this->portalUrl,
            'primary_button_text' => $this->primaryButtonText,
            'secondary_button_text' => $this->secondaryButtonText,
            'secondary_button_url' => $this->secondaryButtonUrl,
            'welcome_title' => $this->welcomeTitle,
            'welcome_description' => $this->welcomeDescription,
            'mayor_message_title' => $this->mayorMessageTitle,
            'mayor_message' => $this->mayorMessage,
            'mayor_image_path' => $this->mayorImagePath,
            'show_mayor_message' => $this->showMayorMessage,
            'contact_cta_title' => $this->contactCtaTitle,
            'contact_cta_description' => $this->contactCtaDescription,
            'contact_cta_button_text' => $this->contactCtaButtonText,
            'contact_cta_button_url' => $this->contactCtaButtonUrl,
            'updated_by' => $this->updatedBy,
        ], fn ($value) => $value !== null);
    }
}
