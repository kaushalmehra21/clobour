<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\ChargeController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\ComplaintCategoryController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Colony\DashboardController;

/*
|--------------------------------------------------------------------------
| Colony Admin Routes
|--------------------------------------------------------------------------
|
| Routes for Colony Admin panel - tenant-specific routes
|
*/

Route::middleware(['auth', 'colony.context', 'colony.access'])->prefix('colony')->name('colony.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Units & Residents
    Route::resource('units', UnitController::class);
    Route::get('units/{unit}/residents', [UnitController::class, 'getResidents'])->name('units.residents');
    Route::resource('residents', ResidentController::class);
    
    // Billing & Payments
    Route::get('billing/generate', [BillingController::class, 'generate'])->name('billing.generate');
    Route::post('billing/generate', [BillingController::class, 'generate']);
    Route::resource('billing', BillingController::class)->only(['index', 'show', 'destroy']);
    Route::resource('charges', ChargeController::class);
    Route::resource('payments', PaymentController::class);
    
    // Expenses
    Route::resource('expenses', ExpenseController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::resource('vendors', VendorController::class);
    
    // Complaints
    Route::resource('complaints', ComplaintController::class)->except(['create', 'store', 'edit']);
    Route::post('complaints/{complaint}/comment', [ComplaintController::class, 'addComment'])->name('complaints.comment');
    Route::resource('complaint-categories', ComplaintCategoryController::class);
    
    // Visitors & Security
    Route::resource('visitors', VisitorController::class)->only(['index', 'show']);
    Route::post('visitors/{visitor}/approve', [VisitorController::class, 'approve'])->name('visitors.approve');
    Route::post('visitors/{visitor}/reject', [VisitorController::class, 'reject'])->name('visitors.reject');
    Route::post('visitors/log-entry', [VisitorController::class, 'logEntry'])->name('visitors.log-entry');
    Route::get('visitors/logs', [VisitorController::class, 'logs'])->name('visitors.logs');
    Route::post('visitor-logs/{log}/exit', [VisitorController::class, 'logExit'])->name('visitor-logs.exit');
    Route::resource('vehicles', VehicleController::class);
    
    // Amenities
    Route::resource('amenities', AmenityController::class);
    Route::resource('bookings', BookingController::class)->only(['index', 'show', 'destroy']);
    Route::post('bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Notices
    Route::resource('notices', NoticeController::class);
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/operational', [ReportController::class, 'operational'])->name('reports.operational');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

