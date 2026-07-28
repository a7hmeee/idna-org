<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreWaterScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('water.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'waterAreaId' => ['required', 'integer', 'exists:water_areas,id'],
            'scheduleDate' => ['required', 'date'],
            'startTime' => ['nullable', 'string', 'max:10'],
            'endTime' => ['nullable', 'string', 'max:10'],
            'status' => ['nullable', 'string', 'in:available,low_pressure,maintenance,emergency,no_water'],
            'notes' => ['nullable', 'string', 'max:500'],
            'displayOrder' => ['nullable', 'integer', 'min:0'],
            'isPublic' => ['nullable', 'boolean'],
        ];
    }
}
