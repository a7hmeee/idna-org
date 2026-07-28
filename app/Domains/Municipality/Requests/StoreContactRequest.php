<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Requests;

use App\Domains\Municipality\Enums\ContactType;
use Illuminate\Foundation\Http\FormRequest;

final class StoreContactRequest extends FormRequest
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
            'type' => ['required', 'string', 'in:' . implode(',', ContactType::values())],
            'label' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'string', 'url', 'max:500'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'نوع جهة الاتصال مطلوب.',
            'type.in' => 'نوع جهة الاتصال غير صالح.',
            'label.required' => 'التسمية مطلوبة.',
            'label.max' => 'التسمية لا يجب أن تتجاوز 255 حرف.',
            'icon.max' => 'الأيقونة لا يجب أن تتجاوز 100 حرف.',
            'url.url' => 'الرابط يجب أن يكون رابطاً صحيحاً.',
            'url.max' => 'الرابط لا يجب أن يتجاوز 500 حرف.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'display_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
