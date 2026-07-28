<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'remember' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => trans('auth.validation.email_required'),
            'email.email' => trans('auth.validation.email_invalid'),
            'password.required' => trans('auth.validation.password_required'),
            'password.min' => trans('auth.validation.password_min'),
        ];
    }
}
