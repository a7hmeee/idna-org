<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreFacilityCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('facility_categories.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'displayOrder' => ['nullable', 'integer', 'min:0'],
            'isActive' => ['nullable', 'boolean'],
        ];
    }
}
