<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\FollowResource;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\HasUser;

class UserController extends Controller
{
    use HasUser;

    /**
     * Obtener perfil de usuario por ID
     */
    public function getUserProfile($id)
    {
        $user = User::with(['profile'])
            ->findOrFail($id);

        $authUser = $this->authenticatedUser();
        $isMe = $user->belongsToAuthenticatedUser();
        $isFollowed = false;
        if ($authUser && !$isMe && $user->private_profile) {
            // Verifica si el usuario autenticado sigue al usuario consultado y está aceptado
            $isFollowed = $user->followers()
                ->where('users.id', $authUser->id)
                ->wherePivot('status', 'accepted')
                ->exists();
        }

        // Si el perfil es privado, no es el propio usuario y no le sigue, solo devolver datos mínimos
        if ($user->private_profile && !$isMe && !$isFollowed) {
            return response()->json(['data' => [new UserResource($user)], 'stats' => []]);
        }

        // Solo calcular estadísticas si el perfil no es privado, es el propio usuario o le sigue
        $distinctLocations = count($user->reviewedLocationIds());
        $distinctBeers = count($user->reviewedBeerIds());
        $distinctStyles = count($user->reviewedStyleIds());
        $distinctBreweries = count($user->reviewedBreweryIds());
        $distinctCountries = count($user->reviewedStyleCountries());
        $totalReviews = $user->beerReviews()->count();
        $totalFavorites = $user->favoritedBeers()->count();

        // Contar solo seguidores y seguidos aceptados
        $followersCount = $user->followers()->wherePivot('status', 'accepted')->count();
        $followingCount = $user->following()->wherePivot('status', 'accepted')->count();
        $postsCount = $user->posts()->count();

        return response()->json([
            'data' => [new UserResource($user)],
            'stats' => [
                'distinct_locations' => $distinctLocations,
                'distinct_beers' => $distinctBeers,
                'distinct_styles' => $distinctStyles,
                'distinct_breweries' => $distinctBreweries,
                'distinct_countries' => $distinctCountries,
                'total_reviews' => $totalReviews,
                'total_favorites' => $totalFavorites,
                'followers' => $followersCount,
                'following' => $followingCount,
                'posts' => $postsCount,
            ]
        ]);
    }

    /**
     * Muestra los posts de un usuario específico
     */
    public function getUserPosts($id): JsonResponse
    {
        $user = User::with(['profile'])
            ->withCount(['followers', 'following', 'posts', 'beerReviews', 'comments'])
            ->findOrFail($id);

        $authUser = $this->authenticatedUser();
        $isMe = $user->belongsToAuthenticatedUser();
        $isFollowed = false;
        if ($authUser && !$isMe && $user->private_profile) {
            // Verifica si el usuario autenticado sigue al usuario consultado y está aceptado
            $isFollowed = $user->followers()
                ->where('users.id', $authUser->id)
                ->wherePivot('status', 'accepted')
                ->exists();
        }

        // Si el perfil es privado, no es el propio usuario y no le sigue, solo devolver datos mínimos
        if ($user->private_profile && !$isMe && !$isFollowed) {
            return response()->json([
                'posts' => [
                    'data' => [],
                ],
                'is_private' => true,
                'message' => 'Este perfil es privado. Debes seguir al usuario para ver sus publicaciones.'
            ]);
        }

        $posts = $user->posts()
            ->withCount(['likes', 'comments'])
            ->with(['beer', 'location', 'user'])
            ->latest()
            ->paginate(15);

        $posts->getCollection()->transform(function ($post) {
            return new PostResource($post);
        });
        return response()->json([
            'posts' => $posts
        ]);
    }

    /**
     * Devuelve una lista simple de seguidores de un usuario (solo id, name, username, profile_picture)
     */
    public function getUserFollowers(Request $request, $id): JsonResponse
    {
        $user = User::with('profile')->findOrFail($id);
        $authUser = $this->authenticatedUser();

        // Comprobar privacidad
        if ($user->profile && $user->profile->private_profile) {
            $isSelf = $authUser && $authUser->id === (int)$id;
            if (!$isSelf) {
                $isAcceptedFollower = false;
                if ($authUser) {
                    $isAcceptedFollower = $user->followers()
                        ->where('users.id', $authUser->id)
                        ->wherePivot('status', 'accepted')
                        ->exists();
                }
                if (!$isAcceptedFollower) {
                    return response()->json([
                        'message' => 'Este perfil es privado. Solo seguidores aceptados pueden ver esta información.'
                    ], 403);
                }
            }
        }

        $followers = $user->followers()
            ->wherePivot('status', 'accepted')
            ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
            ->get();

        return response()->json($followers);
    }

    /**
     * Devuelve una lista simple de usuarios seguidos por un usuario (solo id, name, username, profile_picture)
     */
    public function getUserFollowing(Request $request, $id): JsonResponse
    {
        $user = User::with('profile')->findOrFail($id);
        $authUser = $this->authenticatedUser();

        // Comprobar privacidad
        if ($user->profile && $user->profile->private_profile) {
            $isSelf = $authUser && $authUser->id === (int)$id;
            if (!$isSelf) {
                $isAcceptedFollower = false;
                if ($authUser) {
                    $isAcceptedFollower = $user->followers()
                        ->where('users.id', $authUser->id)
                        ->wherePivot('status', 'accepted')
                        ->exists();
                }
                if (!$isAcceptedFollower) {
                    return response()->json([
                        'message' => 'Este perfil es privado. Solo seguidores aceptados pueden ver esta información.'
                    ], 403);
                }
            }
        }

        $following = $user->following()
            ->wherePivot('status', 'accepted')
            ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
            ->get();

        return response()->json($following);
    }
}
