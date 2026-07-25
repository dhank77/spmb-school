<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Livewire\Admin\Pipeline;
use App\Livewire\Admin\RolePermissionSettings;
use App\Livewire\Admission\Billing;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Student billing page — accessible even before payment
    Route::get('/billing', Billing::class)->name('billing');

    // Admin routes
    Route::middleware(['can:access.admin_portal'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/pipeline', Pipeline::class)->name('pipeline');
        Route::get('/roles', RolePermissionSettings::class)->name('roles');
    });
});

require __DIR__.'/settings.php';
