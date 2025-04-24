<?php

namespace App\Http\Controllers\API;

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

        if ($currentUser->id == $id) {
            return response()->json(['message' => 'No puedes seguirte a ti mismo.'], 400);
        }

        $alreadyFollowing = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $id)
            ->exists();

        if ($alreadyFollowing) {
            return response()->json(['message' => 'Ya sigues a este usuario.'], 409);
        }

        $follow = Follow::create([
            'follower_id' => $currentUser->id,
            'following_id' => $id,
            'accepted' => true
        ]);

        return response()->json([
            'message' => 'Ahora sigues a este usuario.',
            'data' => new FollowResource($follow)
        ]);
    }

    public function unfollow(Request $request, $id): JsonResponse
    {
        $currentUser = $request->user();

        $follow = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $id)
            ->first();

        if ($follow) {
            $follow->unfollowed_at = now();
            $follow->save();
            return response()->json(['message' => 'Has dejado de seguir a este usuario.']);
        } else {
            return response()->json(['message' => 'No estás siguiendo a este usuario.'], 404);
        }
    }

    // Obtener los usuarios a los que sigue el usuario autenticado
    public function followings(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        $followings = Follow::with(['following', 'follower'])
            ->where('follower_id', $currentUser->id)
            ->whereNull('unfollowed_at')
            ->get();

        return response()->json(FollowResource::collection($followings));
    }

    // Obtener los seguidores del usuario autenticado
    public function followers(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        $followers = Follow::with(['following', 'follower'])
            ->where('following_id', $currentUser->id)
            ->whereNull('unfollowed_at')
            ->get();

        return response()->json(FollowResource::collection($followers));
    }
}
