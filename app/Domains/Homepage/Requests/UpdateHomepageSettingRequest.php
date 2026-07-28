<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateHomepageSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('homepage.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'siteTitle' => ['nullable', 'string', 'max:255'],
            'siteSubtitle' => ['nullable', 'string', 'max:500'],
            'portalUrl' => ['nullable', 'string', 'url', 'max:500'],
            'primaryButtonText' => ['nullable', 'string', 'max:255'],
            'secondaryButtonText' => ['nullable', 'string', 'max:255'],
            'secondaryButtonUrl' => ['nullable', 'string', 'max:500'],
            'welcomeTitle' => ['nullable', 'string', 'max:255'],
            'welcomeDescription' => ['nullable', 'string'],
            'mayorMessageTitle' => ['nullable', 'string', 'max:255'],
            'mayorMessage' => ['nullable', 'string'],
            'showMayorMessage' => ['nullable', 'boolean'],
            'contactCtaTitle' => ['nullable', 'string', 'max:255'],
            'contactCtaDescription' => ['nullable', 'string', 'max:1000'],
            'contactCtaButtonText' => ['nullable', 'string', 'max:255'],
            'contactCtaButtonUrl' => ['nullable', 'string', 'max:500'],
        ];
    }
}
