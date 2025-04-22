<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BreweryController;
use App\Http\Controllers\API\BeerStyleController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\FeedController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\FavoriteController;

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

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/search', [SearchController::class, 'search']);

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::put('/auth/update-profile', [AuthController::class, 'updateProfile']);
        Route::put('/auth/change-password', [AuthController::class, 'changePassword']);

        // Usuarios
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::get('/users/me', [UserController::class, 'me']);

        // Relaciones sociales (usando SocialController)
        Route::post('/users/{id}/follow', [SocialController::class, 'follow']);
        Route::delete('/users/{id}/unfollow', [SocialController::class, 'unfollow']);
        Route::get('/users/{id}/followers', [SocialController::class, 'followers']);
        Route::get('/users/{id}/following', [SocialController::class, 'followings']);

        // Feed social
        Route::get('/feed', [FeedController::class, 'feedGeneral']);
        Route::get('/feed/friends', [FeedController::class, 'feedAmigos']);

        // Favorites (rutas REST adicionales)
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites', [FavoriteController::class, 'store']);
        Route::get('/favorites/{id}', [FavoriteController::class, 'show']);
        Route::put('/favorites/{id}', [FavoriteController::class, 'update']);
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

        // Cervecerías
        Route::post('/breweries', [BreweryController::class, 'index']);
        Route::put('/breweries/{id}', [BreweryController::class, 'show']);

        // Estilos de cerveza
        Route::post('/beer-styles', [BeerStyleController::class, 'get']);
        Route::put('/beer-styles/{id}', [BeerStyleController::class, 'show']);

        // Ubicaciones
        Route::post('/locations', [LocationController::class, 'index']);
        Route::put('/locations/{id}', [LocationController::class, 'show']);

        // Posts - Rutas RESTful completas
        Route::get('/posts', [PostController::class, 'index']);
        Route::get('/posts/{id}', [PostController::class, 'show']);
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{id}', [PostController::class, 'update']);
        Route::patch('/posts/{id}', [PostController::class, 'update']);
        Route::delete('/posts/{id}', [PostController::class, 'destroy']);

        // Comentarios - Rutas RESTful
        Route::get('/posts/{id}/comments', [CommentController::class, 'index']);
        Route::get('/comments/{id}', [CommentController::class, 'show']);
        Route::post('/posts/{id}/comments', [CommentController::class, 'store']);
        Route::put('/comments/{id}', [CommentController::class, 'update']);
        Route::patch('/comments/{id}', [CommentController::class, 'update']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

        // Likes - Rutas RESTful
        Route::get('/posts/{id}/likes', [LikeController::class, 'getPostLikes']);
        Route::post('/posts/{id}/likes', [LikeController::class, 'likePost']);
        Route::delete('/posts/{id}/likes', [LikeController::class, 'unlikePost']);


        // Notificaciones
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/{id}', [NotificationController::class, 'show']);
        Route::post('/notifications', [NotificationController::class, 'store']);
        Route::put('/notifications/{id}', [NotificationController::class, 'update']);
        Route::patch('/notifications/{id}', [NotificationController::class, 'update']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });

    // Rutas específicas para administradores
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index']);
        Route::get('/reports/{id}', [ReportController::class, 'show']);
        Route::put('/reports/{id}', [ReportController::class, 'update']);
        Route::patch('/reports/{id}', [ReportController::class, 'update']);
    });
});

// Ruta para probar que la API está funcionando
Route::get('/v1/status', function () {
    return response()->json(['status' => 'online', 'version' => '1.0.0']);
});
