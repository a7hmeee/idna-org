<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateWaterAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('water.update') ?? false;
    }

    public function rules(): array
    {
        $areaId = $this->route('waterArea')?->id ?? $this->route('waterArea');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', "unique:water_areas,slug,{$areaId}"],
            'description' => ['nullable', 'string', 'max:1000'],
            'displayOrder' => ['nullable', 'integer', 'min:0'],
            'isActive' => ['nullable', 'boolean'],
        ];
    }
}
