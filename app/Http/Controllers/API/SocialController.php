<?php

namespace App\Http\Controllers\API;

use App\Events\UserFollowed;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\FollowResource;

class SocialController extends Controller
{
    public function follow(Request $request, $id): JsonResponse
    {
        $currentUser = $request->user();
        logger()->info('Intentando seguir usuario', ['current_user_id' => $currentUser->id, 'target_user_id' => $id]);

        if (
            Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $id)
            ->exists()
        ) {
            logger()->warning('Ya sigue a este usuario', ['current_user_id' => $currentUser->id, 'target_user_id' => $id]);
            return response()->json(['message' => 'Ya sigues a este usuario.'], 409);
        }

        $followedUser = User::find($id);

        if (!$followedUser) {
            logger()->error('Usuario a seguir no encontrado', ['target_user_id' => $id]);
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        $currentUser->following()->attach($id);
        logger()->info('Usuario seguido correctamente', ['current_user_id' => $currentUser->id, 'target_user_id' => $id]);

        event(new UserFollowed($currentUser, $followedUser));
        logger()->info('Evento UserFollowed disparado', ['current_user_id' => $currentUser->id, 'target_user_id' => $id]);

        return response()->json([
            'message' => 'Ahora sigues a este usuario.',
        ]);
    }

    public function unfollow(Request $request, $id): JsonResponse
    {
        $currentUser = $request->user();

        $follow = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $id)
            ->first();

        if (!$follow) {
            return response()->json(['message' => 'No estás siguiendo a este usuario.'], 404);
        }

        $follow->delete();

        return response()->json(['message' => 'Has dejado de seguir a este usuario.']);
    }

    // Obtener los usuarios a los que sigue el usuario autenticado
    public function followings(Request $request): JsonResponse
    {
        $followings = Follow::with(['following', 'follower'])
            ->where('follower_id', $request->user()->id)
            ->get();

        return response()->json(FollowResource::collection($followings));
    }

    // Obtener los seguidores del usuario autenticado
    public function followers(Request $request): JsonResponse
    {
        $followers = Follow::with(['following', 'follower'])
            ->where('following_id', $request->user()->id)
            ->get();

        return response()->json(FollowResource::collection($followers));
    }
}
