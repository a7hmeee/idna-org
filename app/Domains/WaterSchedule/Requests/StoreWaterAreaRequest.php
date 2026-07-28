<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreWaterAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('water.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:water_areas,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'displayOrder' => ['nullable', 'integer', 'min:0'],
            'isActive' => ['nullable', 'boolean'],
        ];
    }
}
