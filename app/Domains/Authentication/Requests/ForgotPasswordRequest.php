<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => trans('auth.validation.email_required'),
            'email.email' => trans('auth.validation.email_invalid'),
            'email.exists' => trans('auth.validation.email_not_found'),
        ];
    }
}
