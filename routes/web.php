<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\GoogleLoginController;

Route::prefix('api/v1')->group(function () {
    Route::post('/google/mobile-auth', [GoogleLoginController::class, 'handleMobileAuth'])->name('google.mobile-auth');
    Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Ruta para servir archivos de storage
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    return response()->file($fullPath);
})->where('path', '.*');

Route::get('/privacy', function () {
    return response()->file(public_path('privacy.html'));
});

Route::get('/terms', function () {
    return response()->file(public_path('terms.html'));
});
