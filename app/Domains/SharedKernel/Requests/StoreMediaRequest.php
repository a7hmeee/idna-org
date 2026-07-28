<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Requests;

use App\Domains\SharedKernel\Enums\MediaCollection;
use Illuminate\Foundation\Http\FormRequest;

final class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('municipality.media.create')
            || $this->user()->can('municipality.media.update');
    }

    public function rules(): array
    {
        $collection = $this->input('collection');

        [$maxSize, $allowedMimes] = match ($collection) {
            'logo', 'white_logo', 'dark_logo', 'favicon', 'mobile_logo' => [2048, 'jpg,jpeg,png,gif,webp,svg'],
            'hero', 'cover', 'banner', 'gallery' => [5120, 'jpg,jpeg,png,gif,webp'],
            default => [10240, 'jpg,jpeg,png,gif,webp,svg,pdf,doc,docx'],
        };

        return [
            'collection' => ['required', 'string', 'in:' . implode(',', MediaCollection::values())],
            'file' => ['required', 'file', "max:{$maxSize}", "mimes:{$allowedMimes}"],
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'collection.required' => 'المجموعة مطلوبة.',
            'collection.in' => 'المجموعة المحددة غير صالحة.',
            'file.required' => 'الملف مطلوب.',
            'file.max' => 'حجم الملف كبير جداً. الشعارات والأيقونات حد أقصى 2 ميجابايت، الصور الكبيرة حد أقصى 5 ميجابايت.',
            'file.mimes' => 'نوع الملف غير مدعوم. الأنواع المدعومة: jpg, jpeg, png, gif, webp, svg, pdf, doc, docx.',
            'title.max' => 'العنوان لا يجب أن يتجاوز 255 حرف.',
            'alt.max' => 'النص البديل لا يجب أن يتجاوز 255 حرف.',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
            'display_order.min' => 'ترتيب العرض لا يمكن أن يكون سالباً.',
        ];
    }
}
