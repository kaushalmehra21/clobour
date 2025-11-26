<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\StaffBlockRequest;
use App\Http\Requests\Resident\StaffRequest;
use App\Models\Staff;
use App\Models\StaffLog;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    use ApiResponseTrait;

    public function index(): JsonResponse
    {
        $staff = Staff::where('resident_id', Auth::id())->get();
        return response()->json($this->success('Staff listed.', $staff));
    }

    public function store(StaffRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['resident_id'] = Auth::id();
        $payload['colony_id'] = Auth::user()->colony_id;

        $staff = Staff::create($payload);
        return response()->json($this->success('Staff added.', $staff));
    }

    public function block(StaffBlockRequest $request, Staff $staff): JsonResponse
    {
        if ($staff->resident_id !== Auth::id()) {
            return response()->json($this->error('Not authorized.'), 403);
        }

        $staff->update(['is_blocked' => $request->blocked]);
        return response()->json($this->success($request->blocked ? 'Staff blocked.' : 'Staff unblocked.', $staff));
    }

    public function logs(): JsonResponse
    {
        $logs = StaffLog::whereHas('staff', fn($q) => $q->where('resident_id', Auth::id()))->get();
        return response()->json($this->success('Staff logs.', $logs));
    }
}

