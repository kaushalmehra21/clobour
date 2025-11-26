<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Resident\AuthController;

Route::post('resident/login', [AuthController::class, 'login']);
Route::post('resident/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('resident/register', [AuthController::class, 'register']);
Route::post('resident/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware(['auth:sanctum', 'ensure.colony', 'resident.status'])->group(function () {
    Route::post('resident/logout', [AuthController::class, 'logout']);
    Route::post('resident/refresh-token', [AuthController::class, 'refreshToken']);

    Route::get('resident/dashboard', [AuthController::class, 'dashboard']);
    Route::get('resident/profile', [\App\Http\Controllers\Resident\ProfileController::class, 'show']);
    Route::put('resident/profile', [\App\Http\Controllers\Resident\ProfileController::class, 'update']);
    Route::post('resident/profile/photo', [\App\Http\Controllers\Resident\ProfileController::class, 'uploadPhoto']);

    Route::get('resident/flat', [\App\Http\Controllers\Resident\FlatController::class, 'show']);
    Route::post('resident/flat/move-request', [\App\Http\Controllers\Resident\FlatController::class, 'moveRequest']);

    Route::get('resident/family-members', [\App\Http\Controllers\Resident\FamilyMemberController::class, 'index']);
    Route::post('resident/family-members', [\App\Http\Controllers\Resident\FamilyMemberController::class, 'store']);
    Route::put('resident/family-members/{familyMember}', [\App\Http\Controllers\Resident\FamilyMemberController::class, 'update']);
    Route::delete('resident/family-members/{familyMember}', [\App\Http\Controllers\Resident\FamilyMemberController::class, 'destroy']);

    Route::get('resident/staff', [\App\Http\Controllers\Resident\StaffController::class, 'index']);
    Route::post('resident/staff', [\App\Http\Controllers\Resident\StaffController::class, 'store']);
    Route::post('resident/staff/{staff}/block', [\App\Http\Controllers\Resident\StaffController::class, 'block']);
    Route::get('resident/staff/logs', [\App\Http\Controllers\Resident\StaffController::class, 'logs']);

    Route::get('resident/vehicles', [\App\Http\Controllers\Resident\VehicleController::class, 'index']);
    Route::post('resident/vehicles', [\App\Http\Controllers\Resident\VehicleController::class, 'store']);
    Route::delete('resident/vehicles/{vehicle}', [\App\Http\Controllers\Resident\VehicleController::class, 'destroy']);
    Route::get('resident/parking', [\App\Http\Controllers\Resident\VehicleController::class, 'parking']);

    Route::get('resident/dues/current', [\App\Http\Controllers\Resident\BillingController::class, 'currentDues']);
    Route::get('resident/dues/history', [\App\Http\Controllers\Resident\BillingController::class, 'history']);
    Route::get('resident/dues/{bill}/download', [\App\Http\Controllers\Resident\BillingController::class, 'download']);
    Route::post('resident/dues/{bill}/pay', [\App\Http\Controllers\Resident\BillingController::class, 'pay']);
    Route::get('resident/dues/{bill}/receipt', [\App\Http\Controllers\Resident\BillingController::class, 'receipt']);
});
