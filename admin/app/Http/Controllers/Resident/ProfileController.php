<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\ProfileUpdateRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function show(): JsonResponse
    {
        $resident = Auth::user();
        return response()->json($this->success('Profile fetched.', $resident));
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $resident = Auth::user();
        $resident->update($request->validated());

        return response()->json($this->success('Profile updated.', $resident));
    }

    public function uploadPhoto(): JsonResponse
    {
        $resident = Auth::user();

        if (!request()->hasFile('photo')) {
            return response()->json($this->error('No photo uploaded.'), 400);
        }

        $path = request()->file('photo')->store('resident/photos', 'public');
        $resident->photo = $path;
        $resident->save();

        return response()->json($this->success('Photo uploaded.', ['photo_url' => Storage::url($path)]));
    }
}

