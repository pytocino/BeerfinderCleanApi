<?php

use App\Http\Controllers\API\MessageController;
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

// Ruta para probar que la API está funcionando
Route::get('/v1/status', function () {
    return response()->json(['status' => 'online', 'version' => '1.0.0']);
});

// Rutas públicas y protegidas en versión 1 de la API
Route::prefix('v1')->group(function () {
    // Rutas de autenticación públicas
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });

    // Rutas que requieren autenticación
    Route::middleware('auth:sanctum')->group(function () {
        // Búsqueda general
        Route::get('/search', [SearchController::class, 'search']);

        // Rutas de autenticación protegidas
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
            Route::put('/update-profile', [AuthController::class, 'updateProfile']);
            Route::put('/change-password', [AuthController::class, 'changePassword']);
        });

        // Usuarios
        Route::prefix('users')->group(function () {
            Route::get('/me', [UserController::class, 'me']);
            Route::get('/{id}', [UserController::class, 'show']);

            // Relaciones sociales
            Route::post('/{id}/follow', [SocialController::class, 'follow']);
            Route::delete('/{id}/unfollow', [SocialController::class, 'unfollow']);
            Route::get('/{id}/followers', [SocialController::class, 'followers']);
            Route::get('/{id}/following', [SocialController::class, 'followings']);
        });

        // Feed social
        Route::prefix('feed')->group(function () {
            Route::get('/', [FeedController::class, 'feedGeneral']);
            Route::get('/friends', [FeedController::class, 'feedAmigos']);
        });

        // Favoritos
        Route::prefix('favorites')->group(function () {
            Route::get('/', [FavoriteController::class, 'index']);
            Route::post('/', [FavoriteController::class, 'store']);
            Route::get('/{id}', [FavoriteController::class, 'show']);
            Route::put('/{id}', [FavoriteController::class, 'update']);
            Route::delete('/{id}', [FavoriteController::class, 'destroy']);
        });

        // Cervecerías
        Route::prefix('breweries')->group(function () {
            Route::get('/', [BreweryController::class, 'index']);
            Route::get('/{id}', [BreweryController::class, 'show']);
        });

        // Estilos de cerveza
        Route::prefix('beer-styles')->group(function () {
            Route::get('/', [BeerStyleController::class, 'index']);
            Route::get('/{id}', [BeerStyleController::class, 'show']);
        });

        // Ubicaciones
        Route::prefix('locations')->group(function () {
            Route::get('/', [LocationController::class, 'index']);
            Route::get('/{id}', [LocationController::class, 'show']);
        });

        // Posts
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index']);
            Route::post('/', [PostController::class, 'store']);
            Route::get('/{id}', [PostController::class, 'show']);
            Route::put('/{id}', [PostController::class, 'update']);
            Route::patch('/{id}', [PostController::class, 'update']);
            Route::delete('/{id}', [PostController::class, 'destroy']);

            // Comentarios de posts
            Route::get('/{id}/comments', [CommentController::class, 'index']);
            Route::post('/{id}/comments', [CommentController::class, 'store']);

            // Likes de posts
            Route::get('/{id}/likes', [LikeController::class, 'getPostLikes']);
            Route::post('/{id}/likes', [LikeController::class, 'likePost']);
            Route::delete('/{id}/likes', [LikeController::class, 'unlikePost']);
        });

        // Comentarios
        Route::prefix('comments')->group(function () {
            Route::get('/{id}', [CommentController::class, 'show']);
            Route::put('/{id}', [CommentController::class, 'update']);
            Route::patch('/{id}', [CommentController::class, 'update']);
            Route::delete('/{id}', [CommentController::class, 'destroy']);
        });

        //Mensajes directos
        Route::get('/messages/{user}', action: [MessageController::class, 'conversation']);
        Route::post('/messages', [MessageController::class, 'store']);
        Route::put('/messages/{message}/read', [MessageController::class, 'markAsRead']);
        Route::get('/messages', [MessageController::class, 'index']);

        // Notificaciones
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::post('/', [NotificationController::class, 'store']);
            Route::get('/{id}', [NotificationController::class, 'show']);
            Route::put('/{id}', [NotificationController::class, 'update']);
            Route::patch('/{id}', [NotificationController::class, 'update']);
            Route::delete('/{id}', [NotificationController::class, 'destroy']);
            Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
        });
    });

    // Rutas específicas para administradores
    Route::middleware(['auth:sanctum', 'admin'])->prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/{id}', [ReportController::class, 'show']);
        Route::put('/{id}', [ReportController::class, 'update']);
        Route::patch('/{id}', [ReportController::class, 'update']);
    });
});
