<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Admin\Pipeline;
use App\Livewire\Admin\RolePermissionSettings;
use App\Livewire\Admission\Billing;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

// Payment routes — callback is called by Duitku server (no auth/CSRF needed)
// return is called by browser after payment, auth may or may not be present
Route::post('/payment/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback')
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/payment/return', [PaymentController::class, 'return'])
    ->name('payment.return');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Student billing page — accessible even before payment (PaymentRequired middleware excludes this route)
    Route::get('/billing', Billing::class)->name('billing');

    // Admin routes
    Route::middleware(['can:access.admin_portal'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/pipeline', Pipeline::class)->name('pipeline');
        Route::get('/roles', RolePermissionSettings::class)->name('roles');
    });
});

require __DIR__.'/settings.php';
