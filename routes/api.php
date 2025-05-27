<?php
// filepath: /home/pedro/Projects/BeerfinderCleanApi/routes/api.php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\User\MyUserController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Social\ConversationController;
use App\Http\Controllers\API\Content\PostController;
use App\Http\Controllers\API\Content\CommentController;
use App\Http\Controllers\API\Content\LikeController;
use App\Http\Controllers\API\Beer\BeerController;
use App\Http\Controllers\API\Beer\BeerStyleController;
use App\Http\Controllers\API\Location\LocationController;
use App\Http\Controllers\API\Location\NearbyController;
use App\Http\Controllers\API\Search\SearchController;
use App\Http\Controllers\API\Brewery\BreweryController;
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

    // Rutas que requieren autenticación
    Route::middleware('auth:sanctum')->group(function () {
        // Perfil y cuenta del usuario
        Route::prefix('profile')->group(function () {
            // Profile personal
            Route::get('/', [MyUserController::class, 'getMyProfile']);

            // Followers y seguidos
            Route::get('/followers', [MyUserController::class, 'getMyFollowers']);
            Route::get('/following', [MyUserController::class, 'getMyFollowing']);

            // Posts personales
            Route::get('/posts', [MyUserController::class, 'getMyPosts']);

            // Reseñas de cervezas personales
            Route::get('/beer-reviews', [MyUserController::class, 'getMyBeerReviews']);

            // Cervezas favoritas
            Route::get('/favorite-beers', [MyUserController::class, 'getMyFavoriteBeers']);

            // Stats personales
            Route::get('/stats', [MyUserController::class, 'getMyStats']);
        });

        Route::prefix('notifications')->group(function () {
            // Notificaciones
            Route::get('/', [NotificationController::class, 'getMyNotifications']);
        });

        Route::prefix('conversations')->group(function () {
            // Conversaciones
            Route::get('/', [ConversationController::class, 'getMyConversations']);
            // Conversaciones por ID
            Route::get('/{id}', [ConversationController::class, 'getConversationById']);
        });

        Route::prefix('users')->group(function () {
            // Usuario
            Route::get('/{id}', [UserController::class, 'getUserProfile']);

            // Posts de usuario
            Route::get('/{id}/posts', [UserController::class, 'getUserPosts']);

            // Seguidores y seguidos de un usuario
            Route::get('/{id}/followers', [UserController::class, 'getUserFollowers']);
            Route::get('/{id}/following', [UserController::class, 'getUserFollowing']);

            // Stats de usuario
            Route::get('/{id}/stats', [UserController::class, 'getUserStats']);
        });

        // Posts
        Route::prefix('posts')->group(function () {
            // Todos los posts
            Route::get('/', [PostController::class, 'getPosts']);

            // Posts por ID
            Route::get('/{id}', [PostController::class, 'getPostById']);

            // Comentarios
            Route::get('/{id}/comments', [CommentController::class, 'getPostComments']);

            // Likes de posts
            Route::get('/{id}/likes', [LikeController::class, 'getPostLikes']);
            Route::post('/{id}/toggle-like', [LikeController::class, 'togglePostLike']);

            // Crear un post
            Route::post('/', [PostController::class, 'store']);
        });

        // Comentarios
        Route::prefix('comments')->group(function () {
            // CRUD básico de comentarios
            Route::get('/', [CommentController::class, 'index']);
            Route::get('/{id}', [CommentController::class, 'show']);
            Route::post('/posts/{id}', [CommentController::class, 'store']); // Crear comentario en un post
            Route::put('/{id}', [CommentController::class, 'update']);
            Route::delete('/{id}', [CommentController::class, 'destroy']);

            // Funcionalidades de likes en comentarios
            Route::post('/{id}/toggle-like', [CommentController::class, 'toggleLike']);
            Route::get('/{id}/check-like', [CommentController::class, 'checkLike']);
            Route::get('/{id}/likers', [CommentController::class, 'getLikers']);

            // Funcionalidades de administración
            Route::post('/{id}/toggle-pin', [CommentController::class, 'togglePin']);
            Route::get('/{id}/stats', [CommentController::class, 'getStats']);
        });

        // Beer Reviews
        Route::prefix('beer-reviews')->group(function () {
            // Crear una review
            Route::post('/', [\App\Http\Controllers\API\Beer\BeerReviewController::class, 'createBeerReview']);
            // Obtener una review por ID
            Route::get('/{id}', [\App\Http\Controllers\API\Beer\BeerReviewController::class, 'getBeerReviewById']);
            // Crear un post a partir de una review
            Route::post('/{id}/post', [PostController::class, 'createPostFromReview']);
        });

        // Búsqueda global
        Route::get('/search', [SearchController::class, 'search']);

        Route::prefix('beer-styles')->group(function () {
            // Estilos de cerveza
            Route::get('/', [BeerStyleController::class, 'getBeerStyles']);

            // Estilos de cerveza por ID
            Route::get('/{id}', [BeerStyleController::class, 'getBeerStyleById']);
        });

        Route::prefix('beers')->group(function () {
            // Cervezas
            Route::get('/', [BeerController::class, 'getAllBeers']);

            // Cervezas por ID
            Route::get('/{id}', [BeerController::class, 'getBeerById']);
        });

        Route::prefix('locations')->group(function () {
            // Ubicaciones
            Route::get('/', [LocationController::class, 'getLocations']);
            // Ubicaciones por ID
            Route::get('/{id}', [LocationController::class, 'getLocationById']);
        });

        // Rutas para ubicaciones cercanas (requieren autenticación)
        Route::prefix('nearby')->group(function () {
            // Cervezas cercanas basadas en ubicación del usuario
            Route::post('/beers', [NearbyController::class, 'getNearbyBeers']);
            // Ubicaciones cercanas (bares, restaurantes, etc.)
            Route::post('/locations', [NearbyController::class, 'getNearbyLocations']);
            // Estadísticas de proximidad
            Route::post('/stats', [NearbyController::class, 'getNearbyStats']);
        });

        Route::prefix('breweries')->group(function () {
            // Cervecerías
            Route::get('/', [BreweryController::class, 'getBreweries']);
            
            // Cervecerías por ID
            Route::get('/{id}', [BreweryController::class, 'getBreweryById']);
        });
    });
});
