<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreHomepageStatisticRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('homepage.statistics.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'isActive' => ['nullable', 'boolean'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
