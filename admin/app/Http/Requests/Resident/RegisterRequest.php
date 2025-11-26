<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:residents,email',
            'mobile' => 'required|string|digits:10|unique:residents,mobile',
            'flat_id' => 'required|integer|exists:flats,id',
            'password' => 'required|string|min:4|confirmed',
            'colony_code' => 'required|string|exists:colonies,code',
        ];
    }
}

