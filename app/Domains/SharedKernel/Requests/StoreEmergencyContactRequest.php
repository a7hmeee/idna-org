<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreEmergencyContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        $hasId = $this->route('contact') !== null || $this->input('id') !== null;

        return $hasId
            ? $this->user()->can('municipality.contacts.update')
            : $this->user()->can('municipality.contacts.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف.',
            'department.max' => 'القسم لا يجب أن يتجاوز 255 حرف.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 50 حرف.',
            'icon.max' => 'الأيقونة لا يجب أن تتجاوز 100 حرف.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'display_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
