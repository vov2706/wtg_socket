<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'min:3', 'max:255', 'exists:users,email'],
            'password' => ['required', 'string', Password::min(6)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
