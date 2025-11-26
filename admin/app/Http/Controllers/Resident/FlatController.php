<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\FlatMoveRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FlatController extends Controller
{
    use ApiResponseTrait;

    public function show(): JsonResponse
    {
        $resident = Auth::user();
        $flat = $resident->flat;

        return response()->json($this->success('Flat details fetched.', $flat));
    }

    public function moveRequest(FlatMoveRequest $request): JsonResponse
    {
        $payload = $request->validated();

        return response()->json($this->success('Move request submitted.', [
            'type' => $payload['type'],
            'requested_on' => $payload['requested_on'],
        ]));
    }
}

