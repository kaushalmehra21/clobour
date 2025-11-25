<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminUserController;
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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

@include_once('admin_web.php');

Route::get('/', function () {
    return redirect()->route('index');
})->name('/');

Route::prefix('starter-kit')->group(function () {
    Route::view('index', 'admin.color-version.index')->name('index');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');
Route::post('impersonation/stop', [AuthController::class, 'stopImpersonation'])
    ->middleware('auth')
    ->name('impersonation.stop');

// Include Super Admin and Colony routes
require __DIR__.'/super-admin.php';
require __DIR__.'/colony.php';

// Legacy admin routes (keeping for backward compatibility, can be removed later)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('dashboard', 'admin.color-version.index')->name('dashboard');
    Route::resource('admins', AdminUserController::class)->except('show');
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