<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class FlatMoveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:move_in,move_out',
            'requested_on' => 'required|date',
            'notes' => 'sometimes|string|max:1000',
        ];
    }
}

