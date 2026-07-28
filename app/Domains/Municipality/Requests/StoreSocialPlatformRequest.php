<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreSocialPlatformRequest extends FormRequest
{
    public function authorize(): bool
    {
        $hasId = $this->route('platform') !== null || $this->input('id') !== null;

        return $hasId
            ? $this->user()->can('municipality.social.update')
            : $this->user()->can('municipality.social.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'alpha_dash'],
            'icon' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'url', 'max:500'],
            'color' => ['nullable', 'string', 'max:50'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنصة مطلوب.',
            'name.max' => 'اسم المنصة لا يجب أن يتجاوز 100 حرف.',
            'slug.required' => 'الرابط المختصر مطلوب.',
            'slug.alpha_dash' => 'الرابط المختصر يجب أن يحتوي على أحرف وأرقام فقط.',
            'icon.required' => 'الأيقونة مطلوبة.',
            'icon.max' => 'الأيقونة لا يجب أن تتجاوز 100 حرف.',
            'url.required' => 'الرابط مطلوب.',
            'url.url' => 'الرابط يجب أن يكون رابطاً صحيحاً.',
            'url.max' => 'الرابط لا يجب أن يتجاوز 500 حرف.',
            'color.max' => 'اللون لا يجب أن يتجاوز 50 حرف.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'display_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
