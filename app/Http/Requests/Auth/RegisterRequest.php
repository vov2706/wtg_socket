<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'min:3', 'max:255'],
            'password' => ['required', 'string', Password::min(6), 'confirmed'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
