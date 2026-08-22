<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Requests;

use App\Domains\Homepage\Enums\PageCarouselKey;
use Illuminate\Foundation\Http\FormRequest;

final class StoreHomepageSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('homepage.slides.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'pageKey' => ['required', 'string', 'in:'.implode(',', PageCarouselKey::values())],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'buttonText' => ['nullable', 'string', 'max:255'],
            'buttonUrl' => ['nullable', 'string', 'max:500'],
            'secondaryButtonText' => ['nullable', 'string', 'max:255'],
            'secondaryButtonUrl' => ['nullable', 'string', 'max:500'],
            'badgeText' => ['nullable', 'string', 'max:255'],
            'isActive' => ['nullable', 'boolean'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
            'startsAt' => ['nullable', 'date'],
            'endsAt' => ['nullable', 'date', 'after_or_equal:startsAt'],
        ];
    }
}
