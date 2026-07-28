<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('water.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'startsAt' => ['required', 'date'],
            'endsAt' => ['required', 'date', 'after_or_equal:startsAt'],
            'status' => ['nullable', 'string', 'max:50'],
            'affectedAreas' => ['nullable', 'array'],
            'affectedAreas.*' => ['string', 'max:255'],
            'isPublic' => ['nullable', 'boolean'],
        ];
    }
}
