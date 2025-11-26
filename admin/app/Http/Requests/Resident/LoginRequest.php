<?php

namespace App\Http\Requests\Resident;

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
            'mobile' => 'nullable|string|digits:10',
            'email' => 'nullable|email',
            'password' => 'nullable|string|min:4',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.digits' => 'Mobile must be 10 digits.',
        ];
    }
}

