<?php

use App\Http\Controllers\BillingExportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Admin\Pipeline;
use App\Livewire\Admin\RolePermissionSettings;
use App\Livewire\Admission\Billing;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/payment/return', [PaymentController::class, 'return'])
    ->name('payment.return');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Student billing page — accessible even before payment (PaymentRequired middleware excludes this route)
    Route::get('/billing', Billing::class)->name('billing');
    Route::get('/billing/export/pdf', [BillingExportController::class, 'downloadPdf'])->name('billing.export.pdf');
    Route::get('/billing/export/excel', [BillingExportController::class, 'downloadExcel'])->name('billing.export.excel');

    // Admin routes
    Route::middleware(['can:access.admin_portal'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/pipeline', Pipeline::class)->name('pipeline');
        Route::get('/roles', RolePermissionSettings::class)->name('roles');
    });
});

require __DIR__.'/settings.php';
