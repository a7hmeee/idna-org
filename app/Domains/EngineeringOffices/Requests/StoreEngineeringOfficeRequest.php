<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Requests;

use App\Domains\EngineeringOffices\Enums\EngineeringOfficeApprovalStatus;
use App\Domains\EngineeringOffices\Enums\EngineeringOfficeStatus;
use Illuminate\Foundation\Http\FormRequest;

final class StoreEngineeringOfficeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'office_name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:engineering_offices,slug,'.$this->route('office')?->id],
            'engineer_name' => ['nullable', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255', 'unique:engineering_offices,license_number,'.$this->route('office')?->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['string', 'max:255'],
            'approval_status' => ['required', 'string', 'in:'.implode(',', EngineeringOfficeApprovalStatus::values())],
            'status' => ['required', 'string', 'in:'.implode(',', EngineeringOfficeStatus::values())],
            'notes' => ['nullable', 'string', 'max:5000'],
            'expires_at' => ['nullable', 'date'],
            'is_public' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'office_name.required' => 'اسم المكتب مطلوب.',
            'office_name.max' => 'اسم المكتب يجب ألا يتجاوز 255 حرفاً.',
            'slug.unique' => 'الرابط المختصر مستخدم بالفعل.',
            'license_number.unique' => 'رقم الترخيص مستخدم بالفعل.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
            'approval_status.required' => 'حالة الاعتماد مطلوبة.',
            'approval_status.in' => 'حالة الاعتماد غير صالحة.',
            'status.required' => 'الحالة مطلوبة.',
            'status.in' => 'الحالة غير صالحة.',
            'expires_at.date' => 'تاريخ انتهاء الاعتماد غير صالح.',
            'specializations.array' => 'التخصصات يجب أن تكون مصفوفة.',
        ];
    }
}
