<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_number' => 'required|string|max:50',
            'vehicle_type' => 'required|string|max:100',
            'brand' => 'nullable|string|max:200',
            'model' => 'nullable|string|max:200',
            'parking_slot_number' => 'nullable|string|max:50',
        ];
    }
}

