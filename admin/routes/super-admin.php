<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\ColonyController;
use App\Http\Controllers\SuperAdmin\SubscriptionPlanController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
|
| Routes for Super Admin panel - manages all colonies
|
*/

Route::middleware(['auth'])->prefix('super-admin')->name('super-admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Colonies Management
    Route::resource('colonies', ColonyController::class);
    Route::post('colonies/{colony}/suspend', [ColonyController::class, 'suspend'])->name('colonies.suspend');
    Route::post('colonies/{colony}/activate', [ColonyController::class, 'activate'])->name('colonies.activate');
    Route::post('colonies/{colony}/impersonate', [ColonyController::class, 'impersonate'])->name('colonies.impersonate');
    
    // Subscription Plans
    Route::resource('plans', SubscriptionPlanController::class);
    
    // Users Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    
    // Analytics & Reports
    Route::get('analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
});

