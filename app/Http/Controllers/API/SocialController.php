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
        $currentUser = auth()->user();

        $follow = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $id)
            ->exists();

        if ($follow) {
            return response()->json(['message' => 'Ya sigues a este usuario.'], 409);
        }

        $followedUser = User::find($id);

        if (!$followedUser) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        // Si el perfil es privado, el seguimiento queda pendiente (accepted = false)
        $accepted = !$followedUser->private_profile;

        Follow::create([
            'follower_id' => $currentUser->id,
            'following_id' => $id,
            'accepted' => $accepted,
        ]);

        event(new UserFollowed($currentUser, $followedUser));

        if ($accepted) {
            return response()->json(['message' => 'Ahora sigues a este usuario.']);
        } else {
            return response()->json(['message' => 'Solicitud de seguimiento enviada.']);
        }
    }

    public function unfollow(Request $request, $id): JsonResponse
    {
        $currentUser = auth()->user();

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
            ->where('follower_id', auth()->user()->id)
            ->where('accepted', true)
            ->get();

        return response()->json(FollowResource::collection($followings));
    }

    // Obtener los seguidores del usuario autenticado
    public function followers(Request $request): JsonResponse
    {
        $followers = Follow::with(['following', 'follower'])
            ->where('following_id', auth()->user()->id)
            ->where('accepted', true)
            ->get();

        return response()->json(FollowResource::collection($followers));
    }
}
