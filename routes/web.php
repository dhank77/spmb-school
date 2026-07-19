<?php

use App\Http\Controllers\LandingPageController;
use App\Livewire\Admin\Pipeline;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/pipeline', Pipeline::class)->name('pipeline');
    });
});

require __DIR__.'/settings.php';
