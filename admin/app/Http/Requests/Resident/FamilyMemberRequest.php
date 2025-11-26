<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'relation' => 'required|string|max:100',
            'dob' => 'sometimes|date',
            'phone' => 'sometimes|string|max:15',
            'gender' => 'sometimes|in:male,female,other',
            'email' => 'sometimes|email',
        ];
    }
}

