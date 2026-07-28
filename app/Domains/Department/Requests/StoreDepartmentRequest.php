<?php

declare(strict_types=1);

namespace App\Domains\Department\Requests;

use App\Domains\Department\Enums\DepartmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $hasId = $this->route('department') !== null || $this->input('id') !== null;

        return $hasId
            ? $this->user()->can('departments.update')
            : $this->user()->can('departments.create');
    }

    public function rules(): array
    {
        $id = $this->route('department')?->id ?? $this->input('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'manager_position' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'extension' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'office_location' => ['nullable', 'string', 'max:500'],
            'working_hours' => ['nullable', 'string', 'max:500'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(DepartmentStatus::values())],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'is_public' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الدائرة مطلوب.',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف.',
            'short_description.max' => 'الوصف المختصر لا يجب أن يتجاوز 500 حرف.',
            'icon.max' => 'الأيقونة لا يجب أن تتجاوز 100 حرف.',
            'cover_image.image' => 'الملف يجب أن يكون صورة.',
            'cover_image.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, webp.',
            'cover_image.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت.',
            'manager_name.max' => 'اسم المدير لا يجب أن يتجاوز 255 حرف.',
            'manager_position.max' => 'منصب المدير لا يجب أن يتجاوز 255 حرف.',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 50 حرف.',
            'extension.max' => 'رقم التحويلة لا يجب أن يتجاوز 50 حرف.',
            'mobile.max' => 'رقم الجوال لا يجب أن يتجاوز 50 حرف.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صالحاً.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',
            'office_location.max' => 'موقع المكتب لا يجب أن يتجاوز 500 حرف.',
            'working_hours.max' => 'ساعات الدوام لا يجب أن تتجاوز 500 حرف.',
            'status.required' => 'الحالة مطلوبة.',
            'status.in' => 'الحالة المحددة غير صالحة.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'display_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
