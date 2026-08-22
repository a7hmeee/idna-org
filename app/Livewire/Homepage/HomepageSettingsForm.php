<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Actions\UpdateHomepageSettingsAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageSettingData;
use App\Domains\Homepage\Models\HomepageSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class HomepageSettingsForm extends Component
{
    public ?string $portalUrl = null;

    public ?string $primaryButtonText = null;

    public ?string $secondaryButtonText = null;

    public ?string $secondaryButtonUrl = null;

    public ?string $welcomeTitle = null;

    public ?string $welcomeDescription = null;

    public ?string $contactCtaTitle = null;

    public ?string $contactCtaDescription = null;

    public ?string $contactCtaButtonText = null;

    public ?string $contactCtaButtonUrl = null;

    public function mount(): void
    {
        $this->authorize('update', HomepageSetting::class);

        $settings = app(HomepageRepositoryInterface::class)->getSettings();

        $this->portalUrl = $settings->portal_url;
        $this->primaryButtonText = $settings->primary_button_text;
        $this->secondaryButtonText = $settings->secondary_button_text;
        $this->secondaryButtonUrl = $settings->secondary_button_url;
        $this->welcomeTitle = $settings->welcome_title;
        $this->welcomeDescription = $settings->welcome_description;
        $this->contactCtaTitle = $settings->contact_cta_title;
        $this->contactCtaDescription = $settings->contact_cta_description;
        $this->contactCtaButtonText = $settings->contact_cta_button_text;
        $this->contactCtaButtonUrl = $settings->contact_cta_button_url;
    }

    public function save(UpdateHomepageSettingsAction $action): void
    {
        $this->authorize('update', HomepageSetting::class);

        $validated = $this->validate([
            'portalUrl' => ['nullable', 'string', 'max:500'],
            'primaryButtonText' => ['nullable', 'string', 'max:255'],
            'secondaryButtonText' => ['nullable', 'string', 'max:255'],
            'secondaryButtonUrl' => ['nullable', 'string', 'max:500'],
            'welcomeTitle' => ['nullable', 'string', 'max:255'],
            'welcomeDescription' => ['nullable', 'string'],
            'contactCtaTitle' => ['nullable', 'string', 'max:255'],
            'contactCtaDescription' => ['nullable', 'string', 'max:1000'],
            'contactCtaButtonText' => ['nullable', 'string', 'max:255'],
            'contactCtaButtonUrl' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['updatedBy'] = auth()->id();

        $action->execute(HomepageSettingData::fromRequest($validated));

        session()->flash('success', 'تم حفظ إعدادات الصفحة الرئيسية بنجاح.');
    }

    public function render()
    {
        return view('livewire.homepage.settings-form');
    }
}
