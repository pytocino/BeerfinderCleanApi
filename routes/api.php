<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BeerController;
use App\Http\Controllers\API\BreweryController;
use App\Http\Controllers\API\BeerStyleController;
use App\Http\Controllers\API\CheckInController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\FeedController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\StatsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rutas públicas
Route::prefix('v1')->group(function () {
    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

    // Búsqueda pública
    Route::get('/search', [SearchController::class, 'search']);

    // Información pública
    Route::get('/beers', [BeerController::class, 'index']);
    Route::get('/beers/{id}', [BeerController::class, 'show']);
    Route::get('/beers/{id}/similar', [BeerController::class, 'getSimilar']);

    Route::get('/breweries', [BreweryController::class, 'index']);
    Route::get('/breweries/{id}', [BreweryController::class, 'show']);
    Route::get('/breweries/{id}/beers', [BreweryController::class, 'getBeers']);
    Route::get('/breweries/top', [BreweryController::class, 'getTopBreweries']);

    Route::get('/beer-styles', [BeerStyleController::class, 'index']);
    Route::get('/beer-styles/{id}', [BeerStyleController::class, 'show']);
    Route::get('/beer-styles/{id}/beers', [BeerStyleController::class, 'getBeers']);

    Route::get('/locations', [LocationController::class, 'index']);
    Route::get('/locations/{id}', [LocationController::class, 'show']);
    Route::get('/locations/nearby', [LocationController::class, 'getNearby']);
    Route::get('/locations/{id}/beers', [LocationController::class, 'getBeers']);

    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/check-ins/{id}', [CheckInController::class, 'show']);
    Route::get('/check-ins/{id}/comments', [CommentController::class, 'getCheckInComments']);
    Route::get('/beers/{id}/check-ins', [CheckInController::class, 'getBeerCheckIns']);

    // Feed público
    Route::get('/feed/trending/beers', [FeedController::class, 'trendingBeers']);
    Route::get('/feed/trending/breweries', [FeedController::class, 'trendingBreweries']);
    Route::get('/feed/trending/styles', [FeedController::class, 'trendingStyles']);
    Route::get('/feed/beer/{id}', [FeedController::class, 'beerFeed']);

    // Rutas protegidas que requieren autenticación
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::put('/auth/update-profile', [AuthController::class, 'updateProfile']);
        Route::put('/auth/change-password', [AuthController::class, 'changePassword']);

        // Usuarios
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Relaciones sociales (usando SocialController)
        Route::post('/users/{id}/follow', [SocialController::class, 'follow']);
        Route::delete('/users/{id}/unfollow', [SocialController::class, 'unfollow']);
        Route::get('/users/{id}/followers', [SocialController::class, 'followers']);
        Route::get('/users/{id}/following', [SocialController::class, 'following']);

        // Feed social
        Route::get('/feed', [FeedController::class, 'index']);
        Route::get('/feed/friends', [FeedController::class, 'friendsFeed']);
        Route::get('/feed/popular', [FeedController::class, 'popularFeed']);

        // Cervezas
        Route::post('/beers', [BeerController::class, 'store']);
        Route::put('/beers/{id}', [BeerController::class, 'update']);
        Route::delete('/beers/{id}', [BeerController::class, 'destroy']);
        Route::post('/beers/{id}/favorite', [BeerController::class, 'favorite']);
        Route::delete('/beers/{id}/unfavorite', [BeerController::class, 'unfavorite']);
        Route::get('/favorites', [BeerController::class, 'favorites']);

        // Cervecerías
        Route::post('/breweries', [BreweryController::class, 'store']);
        Route::put('/breweries/{id}', [BreweryController::class, 'update']);
        Route::delete('/breweries/{id}', [BreweryController::class, 'destroy']);

        // Estilos de cerveza
        Route::post('/beer-styles', [BeerStyleController::class, 'store']);
        Route::put('/beer-styles/{id}', [BeerStyleController::class, 'update']);
        Route::delete('/beer-styles/{id}', [BeerStyleController::class, 'destroy']);

        // Check-ins
        Route::get('/check-ins', [CheckInController::class, 'index']);
        Route::post('/check-ins', [CheckInController::class, 'store']);
        Route::put('/check-ins/{id}', [CheckInController::class, 'update']);
        Route::delete('/check-ins/{id}', [CheckInController::class, 'destroy']);
        Route::get('/users/{id}/check-ins', [CheckInController::class, 'getUserCheckIns']);

        // Ubicaciones
        Route::post('/locations', [LocationController::class, 'store']);
        Route::put('/locations/{id}', [LocationController::class, 'update']);
        Route::delete('/locations/{id}', [LocationController::class, 'destroy']);
        Route::post('/locations/{id}/beers', [LocationController::class, 'addBeer']);
        Route::delete('/locations/{id}/beers/{beer_id}', [LocationController::class, 'removeBeer']);

        // Comentarios
        Route::post('/check-ins/{id}/comments', [CommentController::class, 'store']);
        Route::put('/comments/{id}', [CommentController::class, 'update']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

        // Likes
        Route::post('/check-ins/{id}/like', [LikeController::class, 'likeCheckIn']);
        Route::delete('/check-ins/{id}/unlike', [LikeController::class, 'unlikeCheckIn']);
        Route::get('/check-ins/{id}/likes', [LikeController::class, 'getLikes']);

        // Notificaciones
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

        // Reportes
        Route::post('/reports', [ReportController::class, 'store']);

        // Estadísticas y recomendaciones (usando StatsController)
        Route::get('/users/{id}/stats', [StatsController::class, 'getUserStats']);
        Route::get('/recommendations', [StatsController::class, 'getRecommendations']);
    });

    // Rutas específicas para administradores
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index']);
        Route::get('/reports/{id}', [ReportController::class, 'show']);
        Route::put('/reports/{id}', [ReportController::class, 'update']);
    });
});

// Ruta para probar que la API está funcionando
Route::get('/v1/status', function () {
    return response()->json(['status' => 'online', 'version' => '1.0.0']);
});
