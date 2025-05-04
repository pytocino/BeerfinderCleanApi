<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\FollowResource;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Muestra la información de un usuario específico
     */
    public function show(int $id): JsonResponse
    {
        $user = User::with(['profile'])->withCount(['followers', 'following', 'posts'])->findOrFail($id);
        return response()->json(new UserResource($user));
    }

    /**
     * Muestra los posts de un usuario específico
     */
    public function getUserPosts(Request $request): JsonResponse
    {
        $userId = $request->route('id');
        $user = User::with('profile')->findOrFail($userId);
        $authUser = auth()->user();

        // Comprobar si el perfil es privado
        if ($user->profile && $user->profile->private_profile) {
            // Siempre permitir al dueño del perfil ver sus propios posts
            $isSelf = $authUser && $authUser->id === (int)$userId;

            if (!$isSelf) {
                // Verificar si el usuario autenticado es un seguidor aceptado
                $isAcceptedFollower = false;
                if ($authUser) {
                    $follow = Follow::where('follower_id', '=', $authUser->id)
                        ->where('following_id', '=', $userId)
                        ->where('status', '=', 'accepted')
                        ->exists();
                    $isAcceptedFollower = $follow;
                }

                if (!$isAcceptedFollower) {
                    return response()->json([
                        'message' => 'Este perfil es privado. Solo seguidores aceptados pueden ver los posts.'
                    ], 403);
                }
            }
        }

        $posts = $user->posts()
            ->withCount(['likes', 'comments'])
            ->with(['beer', 'location', 'user'])
            ->latest()
            ->paginate(15);

        return response()->json(\App\Http\Resources\PostResource::collection($posts));
    }

    /**
     * Obtiene los seguidores de un usuario específico (followers)
     */
    public function getFollowers(Request $request): JsonResponse
    {
        $userId = $request->route('id');
        $user = User::with('profile')->findOrFail($userId);
        $authUser = auth()->user();

        // Comprobar si el perfil es privado
        if ($user->profile && $user->profile->private_profile) {
            // Siempre permitir al dueño del perfil ver sus seguidores
            $isSelf = $authUser && $authUser->id === (int)$userId;

            if (!$isSelf) {
                // Verificar si el usuario autenticado es un seguidor aceptado
                $isAcceptedFollower = false;
                if ($authUser) {
                    $isAcceptedFollower = Follow::where('follower_id', '=', $authUser->id)
                        ->where('following_id', '=', $userId)
                        ->where('status', '=', 'accepted')
                        ->exists();
                }

                if (!$isAcceptedFollower) {
                    return response()->json([
                        'message' => 'Este perfil es privado. Solo seguidores aceptados pueden ver esta información.'
                    ], 403);
                }
            }
        }

        // Solo mostrar seguidores con estado 'accepted'
        $followers = $user->followers()
            ->wherePivot('status', '=', 'accepted')
            ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
            ->paginate(15);

        return response()->json($followers);
    }

    /**
     * Obtiene los usuarios que sigue un usuario específico (following)
     */
    public function getFollowing(Request $request): JsonResponse
    {
        $userId = $request->route('id');
        $user = User::with('profile')->findOrFail($userId);
        $authUser = auth()->user();

        // Comprobar si el perfil es privado
        if ($user->profile && $user->profile->private_profile) {
            // Siempre permitir al dueño del perfil ver a quién sigue
            $isSelf = $authUser && $authUser->id === (int)$userId;

            if (!$isSelf) {
                // Verificar si el usuario autenticado es un seguidor aceptado
                $isAcceptedFollower = false;
                if ($authUser) {
                    $isAcceptedFollower = Follow::where('follower_id', '=', $authUser->id)
                        ->where('following_id', '=', $userId)
                        ->where('status', '=', 'accepted')
                        ->exists();
                }

                if (!$isAcceptedFollower) {
                    return response()->json([
                        'message' => 'Este perfil es privado. Solo seguidores aceptados pueden ver esta información.'
                    ], 403);
                }
            }
        }

        // Solo mostrar seguidos con estado 'accepted'
        $following = $user->following()
            ->wherePivot('status', '=', 'accepted')
            ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
            ->paginate(15);

        return response()->json($following);
    }
}
