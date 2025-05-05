<?php
// filepath: /home/pedro/Projects/BeerfinderCleanApi/routes/api.php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\UserStatsController;
use App\Http\Controllers\API\User\UserNotificationController;
use App\Http\Controllers\API\Social\SocialController;
use App\Http\Controllers\API\Social\ConversationController;
use App\Http\Controllers\API\Social\FeedController;
use App\Http\Controllers\API\Content\PostController;
use App\Http\Controllers\API\Content\CommentController;
use App\Http\Controllers\API\Content\LikeController;
use App\Http\Controllers\API\Beer\BeerController;
use App\Http\Controllers\API\Beer\BeerStyleController;
use App\Http\Controllers\API\Location\LocationController;
use App\Http\Controllers\API\Report\ReportController;
use App\Http\Controllers\API\Search\SearchController;
use Illuminate\Support\Facades\Route;

// Estado de la API
Route::get('/v1/status', function () {
    return response()->json(['status' => 'online', 'version' => '1.0.0'], 200);
});

// Rutas v1
Route::prefix('v1')->group(function () {
    // Rutas de autenticación públicas
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    });

    // Rutas públicas para recursos
    Route::prefix('beer-styles')->group(function () {
        Route::get('/', [BeerStyleController::class, 'index']);
        Route::get('/{id}', [BeerStyleController::class, 'show']);
    });

    Route::prefix('beers')->group(function () {
        Route::get('/', [BeerController::class, 'index']);
        Route::get('/{id}', [BeerController::class, 'show']);
    });

    // Rutas que requieren autenticación
    Route::middleware('auth:sanctum')->group(function () {
        // Perfil y cuenta del usuario
        Route::prefix('account')->group(function () {
            Route::get('/', [AuthController::class, 'me']);
            Route::put('/', [UserController::class, 'updateMyProfile']);
            Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
            Route::post('/logout', [AuthController::class, 'logout']);

            // Estadísticas personales
            Route::get('/stats', [UserStatsController::class, 'getMyStats']);

            // Posts personales
            Route::get('/posts', [UserController::class, 'getMyPosts']);
            Route::get('/feed', [FeedController::class, 'getMyFriendsPosts']);

            // Notificaciones
            Route::prefix('notifications')->group(function () {
                Route::get('/', [UserNotificationController::class, 'getMyNotifications']);
                Route::put('/{id}/read', [UserNotificationController::class, 'markAsRead']);
                Route::put('/read-all', [UserNotificationController::class, 'markAllAsRead']);
            });

            // Conversaciones
            Route::prefix('conversations')->group(function () {
                Route::get('/', [ConversationController::class, 'index']);
                Route::post('/', [ConversationController::class, 'store']);
                Route::get('/{id}', [ConversationController::class, 'show']);
                Route::post('/{id}/messages', [ConversationController::class, 'sendMessage']);
                Route::post('/{id}/participants', [ConversationController::class, 'addParticipants']);
                Route::put('/{id}/read', [ConversationController::class, 'markAllAsRead']);
            });
        });

        // Usuarios
        Route::prefix('users')->group(function () {
            Route::get('/{id}', [UserController::class, 'show']);
            Route::get('/{id}/posts', [UserController::class, 'getUserPosts']);
            Route::get('/{id}/stats', [UserStatsController::class, 'show']);

            // Seguidores y seguidos
            Route::get('/{id}/followers', [UserController::class, 'getFollowers']);
            Route::get('/{id}/following', [UserController::class, 'getFollowing']);

            // Relaciones sociales
            Route::post('/{id}/follow', [SocialController::class, 'follow']);
            Route::delete('/{id}/follow', [SocialController::class, 'unfollow']);
            Route::put('/{id}/follow/accept', [SocialController::class, 'acceptFollow']);
            Route::delete('/{id}/follow/reject', [SocialController::class, 'rejectFollow']);
        });

        // Posts
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index']);
            Route::post('/', [PostController::class, 'store']);
            Route::get('/{id}', [PostController::class, 'show']);
            Route::put('/{id}', [PostController::class, 'update']);
            Route::delete('/{id}', [PostController::class, 'destroy']);

            // Comentarios
            Route::get('/{id}/comments', [CommentController::class, 'index']);
            Route::post('/{id}/comments', [CommentController::class, 'store']);

            // Likes
            Route::post('/{id}/like', [LikeController::class, 'likePost']);
            Route::delete('/{id}/like', [LikeController::class, 'unlikePost']);
        });

        // Reportes
        Route::prefix('reports')->group(function () {
            Route::post('/', [ReportController::class, 'store']);
            Route::get('/my', [ReportController::class, 'getMyReports']);
        });

        // Búsqueda global
        Route::get('/search', [SearchController::class, 'search']);

        // Estilos de cerveza - endpoints adicionales
        Route::prefix('beer-styles')->group(function () {
            Route::get('/{id}/beers', [BeerStyleController::class, 'getBeers']);
        });

        // Cervezas - endpoints adicionales
        Route::prefix('beers')->group(function () {
            Route::post('/', [BeerController::class, 'store']);
            Route::put('/{id}', [BeerController::class, 'update']);
            Route::post('/{id}/rate', [BeerController::class, 'rate']);
            Route::get('/{id}/reviews', [BeerController::class, 'getReviews']);
        });

        // Ubicaciones
        Route::prefix('locations')->group(function () {
            Route::get('/', [LocationController::class, 'index']);
            Route::get('/{id}', [LocationController::class, 'show']);
        });
    });
});
