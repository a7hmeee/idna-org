<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Requests;

use App\Domains\Municipality\Enums\CouncilDecisionStatus;
use App\Domains\Municipality\Enums\CouncilDecisionType;
use Illuminate\Foundation\Http\FormRequest;

final class StoreCouncilDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $hasId = $this->route('councilDecision') !== null || $this->input('id') !== null;

        return $hasId
            ? $this->user()->can('council_decisions.update')
            : $this->user()->can('council_decisions.create');
    }

    public function rules(): array
    {
        $id = $this->route('councilDecision')?->id ?? $this->input('id');

        return [
            'decision_number' => ['required', 'string', 'max:255', 'unique:council_decisions,decision_number,' . ($id ?? 'NULL') . ',id'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:' . implode(',', CouncilDecisionType::values())],
            'status' => ['required', 'string', 'in:' . implode(',', CouncilDecisionStatus::values())],
            'decision_date' => ['nullable', 'date'],
            'session_number' => ['nullable', 'string', 'max:255'],
            'attachment_path' => ['nullable', 'string', 'max:500'],
            'is_public' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
        ];
    }

    public function messages(): array
    {
        return [
            'decision_number.required' => 'رقم القرار مطلوب.',
            'decision_number.unique' => 'رقم القرار مستخدم مسبقاً.',
            'decision_number.max' => 'رقم القرار لا يجب أن يتجاوز 255 حرف.',
            'title.required' => 'عنوان القرار مطلوب.',
            'title.max' => 'العنوان لا يجب أن يتجاوز 255 حرف.',
            'summary.max' => 'الملخص لا يجب أن يتجاوز 500 حرف.',
            'type.required' => 'نوع القرار مطلوب.',
            'type.in' => 'نوع القرار المحدد غير صالح.',
            'status.required' => 'حالة القرار مطلوبة.',
            'status.in' => 'حالة القرار المحددة غير صالحة.',
            'decision_date.date' => 'تاريخ القرار يجب أن يكون تاريخاً صحيحاً.',
            'session_number.max' => 'رقم الجلسة لا يجب أن يتجاوز 255 حرف.',
            'attachment_path.max' => 'رابط المرفق لا يجب أن يتجاوز 500 حرف.',
            'sort_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'sort_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
