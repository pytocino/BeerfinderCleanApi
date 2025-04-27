<?php

use App\Http\Controllers\API\ConversationController;
use App\Http\Controllers\API\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BeerController;
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
    return response()->json(['status' => 'online', 'version' => '1.0.0'], 200);
});

// Rutas públicas y protegidas en versión 1 de la API
Route::prefix('v1')->group(function () {
    // Rutas de autenticación públicas
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    });

    // Rutas que requieren autenticación
    Route::middleware('auth:sanctum')->group(function () {
        // Búsqueda general
        Route::get('/search', [SearchController::class, 'search']);

        // Rutas de autenticación protegidas
        Route::prefix('auth')->group(function () {
            Route::put('/update-profile', [AuthController::class, 'updateProfile']);
            Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::get('/profile', [AuthController::class, 'getMyProfile']);

            Route::prefix('posts')->group(function () {
                Route::get('/', [PostController::class, 'getMyPosts']);
                Route::get('/friends', [PostController::class, 'getMyFriends']);
            });


            Route::prefix('conversations')->group(function () {
                Route::get('/', [ConversationController::class, 'index']);
                Route::get('/{id}', [ConversationController::class, 'show']);
                Route::post('/', [ConversationController::class, 'store']);
                Route::post('/{id}/messages', [ConversationController::class, 'sendMessage']);
                Route::post('/{id}/participants', [ConversationController::class, 'addParticipants']);
                Route::put('/{id}/read', [ConversationController::class, 'markAllAsRead']);
            });

            Route::prefix('notifications')->group(function () {
                Route::get('/notifications', [NotificationController::class, 'getMyNotifications']);
                Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
                Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
            });
            Route::get('/stats', [StatController::class, 'getStats']);
        });

        // Usuarios
        Route::prefix('users')->group(function () {
            Route::get('/{id}', [UserController::class, 'show']);
            Route::get('/{id}/posts', [UserController::class, 'getUserPosts']);
            Route::get('/{id}/followers', [SocialController::class, 'followers']);
            Route::get('/{id}/following', [SocialController::class, 'followings']);

            // Relaciones sociales
            Route::post('/{id}/follow', [SocialController::class, 'follow']);
            Route::delete('/{id}/unfollow', [SocialController::class, 'unfollow']);
        });

        // Posts
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index']);
            Route::post('/', [PostController::class, 'store']);
            Route::get('/{id}', [PostController::class, 'show']);
            Route::put('/{id}', [PostController::class, 'update']);
            Route::patch('/{id}', [PostController::class, 'update']);
            Route::delete('/{id}', [PostController::class, 'destroy']);
            Route::get('/{id}/comments', [CommentController::class, 'index']);
            Route::post('/{id}/comments', [CommentController::class, 'store']);
            Route::post('/{id}/likes', [LikeController::class, 'likePost']);
            Route::delete('/{id}/likes', [LikeController::class, 'unlikePost']);
        });

        // Estilos de cerveza
        Route::prefix('beer-styles')->group(function () {
            Route::get('/{id}', [BeerStyleController::class, 'show']);
            Route::get('/stylelist', [BeerStyleController::class, 'getStyles']);
        });

        // Beers
        Route::prefix('beers')->group(function () {
            Route::get('/{id}', [BeerController::class, 'show']);
            Route::get('/beerlist', [BeerController::class, 'getBeers']);
        });

        // Ubicaciones
        Route::prefix('locations')->group(function () {
            Route::get('/locationlist', [LocationController::class, 'getLocations']);
        });

        // Comentarios
        Route::prefix('comments')->group(function () {
            Route::get('/{id}', [CommentController::class, 'show']);
            Route::put('/{id}', [CommentController::class, 'update']);
            Route::patch('/{id}', [CommentController::class, 'update']);
            Route::delete('/{id}', [CommentController::class, 'destroy']);
        });
    });
});
