<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Requests;

use App\Domains\Municipality\Enums\CustomFieldType;
use Illuminate\Foundation\Http\FormRequest;

final class StoreCustomFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        $hasId = $this->route('field') !== null || $this->input('id') !== null;

        return $hasId
            ? $this->user()->can('municipality.custom-fields.update')
            : $this->user()->can('municipality.custom-fields.create');
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string'],
            'type' => ['required', 'string', 'in:' . implode(',', CustomFieldType::values())],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'key.required' => 'المفتاح مطلوب.',
            'key.max' => 'المفتاح لا يجب أن يتجاوز 255 حرف.',
            'value.required' => 'القيمة مطلوبة.',
            'type.required' => 'النوع مطلوب.',
            'type.in' => 'النوع المحدد غير صالح.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'display_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
