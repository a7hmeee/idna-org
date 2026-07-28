<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'new_password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => trans('auth.validation.current_password_required'),
            'current_password.current_password' => trans('auth.validation.current_password_incorrect'),
            'new_password.required' => trans('auth.validation.password_required'),
            'new_password.min' => trans('auth.validation.password_min'),
            'new_password.confirmed' => trans('auth.validation.password_confirmation_mismatch'),
            'new_password.different' => trans('auth.validation.password_same_as_current'),
        ];
    }
}
