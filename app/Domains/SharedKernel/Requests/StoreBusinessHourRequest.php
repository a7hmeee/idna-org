<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Requests;

use App\Domains\SharedKernel\Enums\BusinessDay;
use Illuminate\Foundation\Http\FormRequest;

final class StoreBusinessHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('municipality.update');
    }

    public function rules(): array
    {
        return [
            'day' => ['required', 'string', 'in:' . implode(',', BusinessDay::values())],
            'opening_time' => ['required_without:is_closed', 'nullable', 'date_format:H:i'],
            'closing_time' => ['required_with:opening_time', 'nullable', 'date_format:H:i', 'after:opening_time'],
            'is_closed' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
        ];
    }

    public function messages(): array
    {
        return [
            'day.required' => 'اليوم مطلوب.',
            'day.in' => 'اليوم المحدد غير صالح.',
            'opening_time.date_format' => 'وقت الفتح يجب أن يكون بصيغة HH:MM.',
            'closing_time.date_format' => 'وقت الإغلاق يجب أن يكون بصيغة HH:MM.',
            'closing_time.after' => 'وقت الإغلاق يجب أن يكون بعد وقت الفتح.',
            'opening_time.required_without' => 'وقت الفتح مطلوب عندما لا يكون اليوم عطلة.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
        ];
    }
}
