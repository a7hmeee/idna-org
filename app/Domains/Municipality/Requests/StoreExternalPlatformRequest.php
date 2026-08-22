<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Requests;

use App\Domains\Municipality\Enums\PlatformCategory;
use Illuminate\Foundation\Http\FormRequest;

final class StoreExternalPlatformRequest extends FormRequest
{
    public function authorize(): bool
    {
        $hasId = $this->route('platform') !== null || $this->input('id') !== null;

        return $hasId
            ? $this->user()->can('municipality.platforms.update')
            : $this->user()->can('municipality.platforms.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'url', 'max:500'],
            'category' => ['nullable', 'string', 'in:'.implode(',', PlatformCategory::values())],
            'color' => ['nullable', 'string', 'max:50'],
            'open_in_new_tab' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنصة مطلوب.',
            'name.max' => 'اسم المنصة لا يجب أن يتجاوز 255 حرف.',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف.',
            'icon.required' => 'الأيقونة مطلوبة.',
            'icon.max' => 'الأيقونة لا يجب أن تتجاوز 100 حرف.',
            'url.required' => 'الرابط مطلوب.',
            'url.url' => 'الرابط يجب أن يكون رابطاً صحيحاً.',
            'url.max' => 'الرابط لا يجب أن يتجاوز 500 حرف.',
            'category.in' => 'التصنيف المحدد غير صالح.',
            'color.max' => 'اللون لا يجب أن يتجاوز 50 حرف.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'display_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
