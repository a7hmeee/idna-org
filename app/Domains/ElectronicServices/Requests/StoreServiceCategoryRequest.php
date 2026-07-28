<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Requests;

use App\Domains\ElectronicServices\Enums\ServiceCategoryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('service_categories')->ignore($this->route('category'))],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(ServiceCategoryStatus::values())],
            'is_public' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم التصنيف مطلوب',
            'name.max' => 'اسم التصنيف يجب ألا يتجاوز 255 حرفاً',
            'parent_id.exists' => 'التصنيف الأب غير موجود',
            'slug.unique' => 'الرابط المختصر مستخدم مسبقاً',
        ];
    }
}
