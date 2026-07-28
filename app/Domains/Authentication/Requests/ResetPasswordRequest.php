<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => trans('auth.validation.token_required'),
            'email.required' => trans('auth.validation.email_required'),
            'email.email' => trans('auth.validation.email_invalid'),
            'password.required' => trans('auth.validation.password_required'),
            'password.min' => trans('auth.validation.password_min'),
            'password.confirmed' => trans('auth.validation.password_confirmation_mismatch'),
        ];
    }
}
