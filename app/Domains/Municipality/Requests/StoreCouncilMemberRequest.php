<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Requests;

use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Enums\CouncilMemberStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCouncilMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $hasId = $this->route('councilMember') !== null || $this->input('id') !== null;

        return $hasId
            ? $this->user()->can('council_members.update')
            : $this->user()->can('council_members.create');
    }

    public function rules(): array
    {
        $id = $this->route('councilMember')?->id ?? $this->input('id');

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'national_number' => ['nullable', 'string', 'max:50'],
            'position' => ['required', 'string', Rule::in(CouncilMemberPosition::values())],
            'qualification' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'facebook' => ['nullable', 'string', 'url', 'max:500'],
            'twitter' => ['nullable', 'string', 'url', 'max:500'],
            'linkedin' => ['nullable', 'string', 'url', 'max:500'],
            'term_start' => ['required', 'date'],
            'term_end' => ['nullable', 'date', 'after_or_equal:term_start'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:99'],
            'committee' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(CouncilMemberStatus::values())],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'is_public' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'الاسم الكامل مطلوب.',
            'full_name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف.',
            'national_number.max' => 'الرقم الوطني لا يجب أن يتجاوز 50 حرف.',
            'position.required' => 'المنصب مطلوب.',
            'position.in' => 'المنصب المحدد غير صالح.',
            'qualification.max' => 'المؤهل لا يجب أن يتجاوز 255 حرف.',
            'profession.max' => 'المهنة لا يجب أن تتجاوز 255 حرف.',
            'photo.image' => 'الملف يجب أن يكون صورة.',
            'photo.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, webp.',
            'photo.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت.',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 50 حرف.',
            'mobile.max' => 'رقم الجوال لا يجب أن يتجاوز 50 حرف.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صالحاً.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',
            'address.max' => 'العنوان لا يجب أن يتجاوز 500 حرف.',
            'facebook.url' => 'رابط فيسبوك يجب أن يكون رابطاً صحيحاً.',
            'twitter.url' => 'رابط تويتر يجب أن يكون رابطاً صحيحاً.',
            'linkedin.url' => 'رابط لينكد إن يجب أن يكون رابطاً صحيحاً.',
            'term_start.required' => 'تاريخ بداية الدورة مطلوب.',
            'term_start.date' => 'تاريخ بداية الدورة يجب أن يكون تاريخاً صحيحاً.',
            'term_end.date' => 'تاريخ نهاية الدورة يجب أن يكون تاريخاً صحيحاً.',
            'term_end.after_or_equal' => 'تاريخ نهاية الدورة يجب أن يكون بعد أو يساوي تاريخ البداية.',
            'years_of_experience.integer' => 'سنوات الخبرة يجب أن تكون رقماً صحيحاً.',
            'years_of_experience.min' => 'سنوات الخبرة لا يمكن أن تكون سالبة.',
            'years_of_experience.max' => 'سنوات الخبرة لا يمكن أن تتجاوز 99.',
            'committee.max' => 'اللجنة لا يجب أن تتجاوز 255 حرف.',
            'status.required' => 'الحالة مطلوبة.',
            'status.in' => 'الحالة المحددة غير صالحة.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'display_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
