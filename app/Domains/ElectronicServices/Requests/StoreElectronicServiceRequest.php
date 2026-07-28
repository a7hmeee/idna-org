<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Requests;

use App\Domains\ElectronicServices\Enums\ElectronicServiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreElectronicServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_category_id' => ['required', 'exists:service_categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('electronic_services')->ignore($this->route('service'))],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'eligibility' => ['nullable', 'string'],
            'requirements' => ['nullable', 'array'],
            'requirements.*.title' => ['required_with:requirements', 'string', 'max:255'],
            'requirements.*.description' => ['nullable', 'string', 'max:500'],
            'requirements.*.is_required' => ['nullable', 'boolean'],
            'documents' => ['nullable', 'array'],
            'documents.*.name' => ['required_with:documents', 'string', 'max:255'],
            'documents.*.description' => ['nullable', 'string', 'max:500'],
            'documents.*.is_required' => ['nullable', 'boolean'],
            'steps' => ['nullable', 'array'],
            'steps.*.title' => ['required_with:steps', 'string', 'max:255'],
            'steps.*.description' => ['nullable', 'string', 'max:500'],
            'fees' => ['nullable', 'array'],
            'fees.*.title' => ['required_with:fees', 'string', 'max:255'],
            'fees.*.amount' => ['nullable', 'string', 'max:50'],
            'fees.*.currency' => ['nullable', 'string', 'max:10'],
            'fees.*.notes' => ['nullable', 'string', 'max:500'],
            'processing_time' => ['nullable', 'string', 'max:255'],
            'portal_url' => ['nullable', 'url', 'max:500'],
            'requires_login' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(ElectronicServiceStatus::values())],
            'is_public' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الخدمة مطلوب',
            'name.max' => 'اسم الخدمة يجب ألا يتجاوز 255 حرفاً',
            'service_category_id.required' => 'التصنيف مطلوب',
            'service_category_id.exists' => 'التصنيف غير موجود',
            'department_id.exists' => 'الدائرة غير موجودة',
            'slug.unique' => 'الرابط المختصر مستخدم مسبقاً',
            'portal_url.url' => 'رابط البوابة يجب أن يكون رابطاً صحيحاً',
            'requirements.*.title.required_with' => 'عنوان المتطلب مطلوب',
            'documents.*.name.required_with' => 'اسم المستند مطلوب',
            'steps.*.title.required_with' => 'عنوان الخطوة مطلوب',
            'fees.*.title.required_with' => 'عنوان الرسم مطلوب',
        ];
    }
}
