<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\GoogleLoginController;
use Illuminate\Support\Facades\Log;

Route::prefix('api/v1')->group(function () {
    Route::post('/google/mobile-auth', [GoogleLoginController::class, 'handleMobileAuth'])->name('google.mobile-auth');
    Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Ruta de prueba
Route::get('/test-route', function () {
    return response()->json(['message' => 'Route is working!']);
});

// Ruta para servir archivos de storage
Route::get('/storage/{path}', function ($path) {
    Log::info('Storage route accessed with path: ' . $path);
    
    $fullPath = storage_path('app/public/' . $path);
    Log::info('Full path: ' . $fullPath);
    Log::info('File exists: ' . (file_exists($fullPath) ? 'yes' : 'no'));
    
    if (!file_exists($fullPath)) {
        Log::error('File not found: ' . $fullPath);
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
