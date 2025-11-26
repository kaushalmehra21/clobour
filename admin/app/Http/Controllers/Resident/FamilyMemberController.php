<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\FamilyMemberRequest;
use App\Models\FamilyMember;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FamilyMemberController extends Controller
{
    use ApiResponseTrait;

    public function index(): JsonResponse
    {
        $members = Auth::user()->familyMembers;
        return response()->json($this->success('Family members listed.', $members));
    }

    public function store(FamilyMemberRequest $request): JsonResponse
    {
        $member = Auth::user()->familyMembers()->create($request->validated());
        return response()->json($this->success('Family member added.', $member));
    }

    public function update(FamilyMemberRequest $request, FamilyMember $familyMember): JsonResponse
    {
        if ($familyMember->resident_id !== Auth::id()) {
            return response()->json($this->error('Not allowed.'), 403);
        }

        $familyMember->update($request->validated());
        return response()->json($this->success('Family member updated.', $familyMember));
    }

    public function destroy(FamilyMember $familyMember): JsonResponse
    {
        if ($familyMember->resident_id !== Auth::id()) {
            return response()->json($this->error('Not allowed.'), 403);
        }

        $familyMember->delete();
        return response()->json($this->success('Family member removed.'));
    }
}

