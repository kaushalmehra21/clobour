<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string|max:500',
            'identity_proof' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}

