<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\ForgotPasswordRequest;
use App\Http\Requests\Resident\LoginRequest;
use App\Http\Requests\Resident\RefreshTokenRequest;
use App\Http\Requests\Resident\RegisterRequest;
use App\Http\Requests\Resident\VerifyOtpRequest;
use App\Models\Colony;
use App\Models\Resident;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!empty($data['email']) && !empty($data['password'])) {
            if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                return response()->json($this->error('Invalid credentials.'), 401);
            }
        } elseif (!empty($data['mobile'])) {
            $resident = Resident::where('mobile', $data['mobile'])->first();
            if (!$resident) {
                return response()->json($this->error('Mobile number not registered.'), 404);
            }
            // Placeholder: send OTP
            return response()->json($this->success('OTP sent.', ['mobile' => $data['mobile']]));
        } else {
            return response()->json($this->error('Provide email/password or mobile.'), 400);
        }

        /** @var Resident $user */
        $user = Auth::user();
        $token = $user->createToken('resident-token')->plainTextToken;

        return response()->json($this->success('Login successful.', [
            'resident' => $user,
            'token' => $token,
        ]));
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $resident = Resident::where('mobile', $request->mobile)->first();

        if (!$resident) {
            return response()->json($this->error('Resident not found.'), 404);
        }

        // Placeholder OTP validation
        $token = $resident->createToken('resident-token')->plainTextToken;
        Auth::login($resident);

        return response()->json($this->success('OTP verified.', [
            'resident' => $resident,
            'token' => $token,
        ]));
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $colony = Colony::where('code', $data['colony_code'])->first();

        $resident = Resident::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'flat_id' => $data['flat_id'],
            'colony_id' => $colony->id,
            'password' => Hash::make($data['password']),
            'status' => 'active',
        ]);

        $token = $resident->createToken('resident-token')->plainTextToken;

        return response()->json($this->success('Registration successful.', [
            'resident' => $resident,
            'token' => $token,
        ]));
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $resident = Resident::where('email', $request->email)->first();

        if (!$resident) {
            return response()->json($this->error('Resident not found.'), 404);
        }

        // Placeholder: send reset link/OTP
        return response()->json($this->success('Reset instructions sent.'));
    }

    public function logout(): JsonResponse
    {
        /** @var Resident $user */
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json($this->success('Logged out successfully.'));
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        $token = PersonalAccessToken::findToken($request->refresh_token);

        if (!$token) {
            return response()->json($this->error('Invalid refresh token.', [], 401));
        }

        $token->delete();
        $newToken = $token->tokenable->createToken('resident-token')->plainTextToken;

        return response()->json($this->success('Token refreshed.', [
            'token' => $newToken,
        ]));
    }

    public function dashboard(): JsonResponse
    {
        $resident = Auth::user();

        $summary = [
            'dues' => 0,
            'notices' => 0,
            'events' => 0,
            'visitors_today' => 0,
        ];

        return response()->json($this->success('Dashboard fetched.', [
            'resident' => $resident,
            'summary' => $summary,
        ]));
    }
}

