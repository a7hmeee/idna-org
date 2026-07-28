<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreGeneralInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('municipality.update');
    }

    public function rules(): array
    {
        return [
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'full_description' => ['nullable', 'string'],
            'vision' => ['nullable', 'string', 'max:1000'],
            'mission' => ['nullable', 'string', 'max:1000'],
            'objectives' => ['nullable', 'array'],
            'objectives.*' => ['string', 'max:500'],
            'foundation_date' => ['nullable', 'date'],
            'population' => ['nullable', 'integer', 'min:0'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'municipality_code' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required' => 'الاسم العربي مطلوب.',
            'name_ar.max' => 'الاسم العربي لا يجب أن يتجاوز 255 حرف.',
            'name_en.required' => 'الاسم الإنجليزي مطلوب.',
            'name_en.max' => 'الاسم الإنجليزي لا يجب أن يتجاوز 255 حرف.',
            'short_description.max' => 'الوصف المختصر لا يجب أن يتجاوز 500 حرف.',
            'vision.max' => 'الرؤية لا يجب أن تتجاوز 1000 حرف.',
            'mission.max' => 'الرسالة لا يجب أن تتجاوز 1000 حرف.',
            'objectives.*.max' => 'كل هدف لا يجب أن يتجاوز 500 حرف.',
            'foundation_date.date' => 'تاريخ التأسيس يجب أن يكون تاريخاً صحيحاً.',
            'population.integer' => 'عدد السكان يجب أن يكون رقماً صحيحاً.',
            'population.min' => 'عدد السكان لا يمكن أن يكون سالباً.',
            'area.numeric' => 'المساحة يجب أن تكون رقماً.',
            'area.min' => 'المساحة لا يمكن أن تكون سالبة.',
            'municipality_code.max' => 'رمز البلدية لا يجب أن يتجاوز 50 حرف.',
            'latitude.between' => 'خط العرض يجب أن يكون بين -90 و 90.',
            'longitude.between' => 'خط الطول يجب أن يكون بين -180 و 180.',
        ];
    }
}
