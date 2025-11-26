<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\VehicleRequest;
use App\Models\Vehicle;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    use ApiResponseTrait;

    public function index(): JsonResponse
    {
        $vehicles = Auth::user()->vehicles;
        return response()->json($this->success('Vehicles retrieved.', $vehicles));
    }

    public function store(VehicleRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['resident_id'] = Auth::id();
        $payload['colony_id'] = Auth::user()->colony_id;

        $vehicle = Vehicle::create($payload);
        return response()->json($this->success('Vehicle added.', $vehicle));
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        if ($vehicle->resident_id !== Auth::id()) {
            return response()->json($this->error('Not allowed.'), 403);
        }
        $vehicle->delete();
        return response()->json($this->success('Vehicle deleted.'));
    }

    public function parking(): JsonResponse
    {
        $parking = [
            'slot' => Auth::user()->flat->parking_slot ?? 'Not assigned',
            'type' => Auth::user()->flat->type ?? 'Flat',
        ];
        return response()->json($this->success('Parking details.', $parking));
    }
}

