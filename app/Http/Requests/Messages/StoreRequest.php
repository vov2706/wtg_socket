<?php

namespace App\Http\Requests\Messages;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receiver_id' => ['required', 'int', 'exists:users,id'],
            'text' => ['required', 'string', 'min:2', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'receiver_id' => 'receiver'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
