<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Duitku server-to-server callback API route (automatically bypasses web/session/CSRF middleware)
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
