<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateHomepageSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('homepage.sections.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'isEnabled' => ['nullable', 'boolean'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
            'itemsLimit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
