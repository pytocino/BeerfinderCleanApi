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
        $user = User::with(['profile'])->findOrFail($id);
        $authUser = $this->authenticatedUser();
        
        // Verificar relaciones básicas
        $isMyProfile = $authUser && $authUser->id == $user->id;
        $followStatus = $this->getFollowStatus($user, $authUser);
        $isFollowingUser = $followStatus === 'accepted';
        
        // Determinar si puede ver el perfil completo
        $canViewFullProfile = !$user->private_profile || $isMyProfile || $isFollowingUser;
        
        // Si no puede ver el perfil completo, devolver datos mínimos
        if (!$canViewFullProfile) {
            return response()->json([
                'data' => new UserResource($user),
                'stats' => [],
                'is_followed' => false,
                'follow_status' => $followStatus,
                'is_private' => true,
                'message' => 'Este perfil es privado'
            ]);
        }
        
        // Calcular estadísticas completas
        $stats = [
            'distinct_locations' => count($user->reviewedLocationIds()),
            'distinct_beers' => count($user->reviewedBeerIds()),
            'distinct_styles' => count($user->reviewedStyleIds()),
            'distinct_breweries' => count($user->reviewedBreweryIds()),
            'distinct_countries' => count($user->reviewedStyleCountries()),
            'total_reviews' => $user->beerReviews()->count(),
            'total_favorites' => $user->favoritedBeers()->count(),
            'followers' => $user->followers()->wherePivot('status', 'accepted')->count(),
            'following' => $user->following()->wherePivot('status', 'accepted')->count(),
            'posts' => $user->posts()->count(),
        ];

        return response()->json([
            'data' => new UserResource($user),
            'stats' => $stats,
            'is_followed' => $isFollowingUser,
            'follow_status' => $followStatus,
            'is_private' => false
        ]);
    }

    /**
     * Muestra los posts de un usuario específico
     */
    public function getUserPosts($id): JsonResponse
    {
        $user = User::with(['profile'])->findOrFail($id);
        $authUser = $this->authenticatedUser();
        
        // Verificar si puede ver los posts
        $canViewPosts = $this->canViewUserContent($user, $authUser);
        $followStatus = $this->getFollowStatus($user, $authUser);
        
        if (!$canViewPosts) {
            $message = $followStatus === 'pending' 
                ? 'Solicitud de seguimiento pendiente. Espera a que el usuario la acepte.'
                : 'Este perfil es privado. Debes seguir al usuario para ver sus publicaciones.';
                
            return response()->json([
                'posts' => ['data' => []],
                'is_private' => true,
                'follow_status' => $followStatus,
                'message' => $message
            ]);
        }

        $posts = $user->posts()
            ->withCount(['likes', 'comments'])
            ->with([
                'beer:id,name,brewery_id,style_id',
                'beer.brewery:id,name',
                'beer.style:id,name',
                'location:id,name,address,city,latitude,longitude',
                'user',
                'beerReview:id,post_id,beer_id,location_id,rating,review_text',
                'beerReview.beer:id,name,brewery_id,style_id',
                'beerReview.beer.brewery:id,name',
                'beerReview.beer.style:id,name',
                'beerReview.location:id,name,address,city,latitude,longitude'
            ])
            ->latest()
            ->paginate(15);

        $posts->getCollection()->transform(function ($post) {
            return new PostResource($post);
        });
        
        return response()->json([
            'posts' => $posts,
            'follow_status' => $followStatus
        ]);
    }

    /**
     * Devuelve seguidores del usuario
     */
    public function getUserFollowers(Request $request, $id): JsonResponse
    {
        $user = User::with('profile')->findOrFail($id);

        $followers = $user->followers()
            ->wherePivotIn('status', ['accepted', 'pending'])
            ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
            ->get()
            ->map(function($follower) {
                $follower->follow_status = $follower->pivot->status;
                return $follower;
            });

        return response()->json([
            'data' => $followers,
        ]);
    }

    /**
     * Devuelve usuarios seguidos
     */
    public function getUserFollowing(Request $request, $id): JsonResponse
    {
        $user = User::with('profile')->findOrFail($id);

        $following = $user->following()
            ->wherePivotIn('status', ['pending', 'accepted'])
            ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
            ->orderByRaw("CASE WHEN user_follows.status = 'pending' THEN 0 ELSE 1 END")
            ->get()
            ->map(function($followedUser) {
                $followedUser->follow_status = $followedUser->pivot->status;
                return $followedUser;
            });

        return response()->json([
            'data' => $following,
        ]);
    }

    /**
     * Obtiene el estado de seguimiento entre el usuario autenticado y el usuario objetivo
     */
    private function getFollowStatus(User $targetUser, ?User $authUser): ?string
    {
        if (!$authUser || $authUser->id == $targetUser->id) {
            return null; // No aplica si no hay usuario autenticado o es el mismo usuario
        }

        $follow = $targetUser->followers()
            ->where('users.id', $authUser->id)
            ->first();

        return $follow ? $follow->pivot->status : null;
    }

    /**
     * Método auxiliar para verificar si se puede ver el contenido del usuario
     */
    private function canViewUserContent(User $user, ?User $authUser): bool
    {
        // Si el perfil no es privado, todos pueden ver
        if (!$user->private_profile) {
            return true;
        }
        
        // Si no hay usuario autenticado, no puede ver perfil privado
        if (!$authUser) {
            return false;
        }
        
        // Si es su propio perfil, puede ver
        if ($authUser->id == $user->id) {
            return true;
        }
        
        // Si sigue al usuario con estado aceptado, puede ver
        return $this->getFollowStatus($user, $authUser) === 'accepted';
    }
}
