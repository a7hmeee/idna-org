<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('facilities.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'facilityCategoryId' => ['nullable', 'integer', 'exists:facility_categories,id'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'coverImage' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'workingHours' => ['nullable', 'string', 'max:255'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string', 'max:500'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:500'],
            'rules' => ['nullable', 'array'],
            'rules.*' => ['string', 'max:500'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'isPublic' => ['nullable', 'boolean'],
            'isFeatured' => ['nullable', 'boolean'],
        ];
    }
}
