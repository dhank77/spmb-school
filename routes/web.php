<?php

use App\Http\Controllers\LandingPageController;
use App\Livewire\Admin\Pipeline;
use App\Livewire\Admin\RolePermissionSettings;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin|super_admin|admissions_officer|finance_staff|cbt_proctor'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/pipeline', Pipeline::class)->name('pipeline');
        Route::get('/roles', RolePermissionSettings::class)->name('roles');
    });
});

require __DIR__.'/settings.php';
