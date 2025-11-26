<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:residents,email,' . auth()->id(),
            'phone' => 'sometimes|string|max:15',
            'alternate_phone' => 'sometimes|string|max:15',
        ];
    }
}

