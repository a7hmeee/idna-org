<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateHomepageQuickLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('homepage.quick_links.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'string', 'max:500'],
            'type' => ['nullable', 'string', 'max:50'],
            'isExternal' => ['nullable', 'boolean'],
            'isActive' => ['nullable', 'boolean'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
