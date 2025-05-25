<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\GoogleLoginController;

Route::prefix('api/v1')->group(function () {
    Route::post('/google/mobile-auth', [GoogleLoginController::class, 'handleMobileAuth'])->name('google.mobile-auth');
    Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');
});
