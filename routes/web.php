<?php

use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\BeerController;
use App\Http\Controllers\BeerStyleController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

// Rutas específicas para administradores
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Reportes
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/{id}', [ReportController::class, 'show']);
        Route::put('/{id}', [ReportController::class, 'update']);
        Route::patch('/{id}', [ReportController::class, 'update']);
    });

    // CRUD de cervezas y estilos de cerveza
    Route::resource('beers', BeerController::class);
    Route::resource('beer-styles', BeerStyleController::class);
    Route::get('/stats', [StatsController::class, 'index']);
});
