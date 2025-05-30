<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:25'],
            'password' => ['required', 'string', 'max:25'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.',
        ];
    }
}
