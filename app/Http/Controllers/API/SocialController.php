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

        Follow::create([
            'follower_id' => $currentUser->id,
            'following_id' => $id,
            'accepted' => true
        ]);

        return response()->json(['message' => 'Ahora sigues a este usuario.']);
    }

    public function unfollow(Request $request, $id): JsonResponse
    {
        $currentUser = $request->user();

        $deleted = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $id)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Has dejado de seguir a este usuario.']);
        } else {
            return response()->json(['message' => 'No estás siguiendo a este usuario.'], 404);
        }
    }

    // Obtener los usuarios a los que sigue el usuario autenticado
    public function followings(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        $followings = User::whereIn('id', function ($query) use ($currentUser) {
            $query->select('following_id')
                ->from('follows')
                ->where('follower_id', $currentUser->id);
        })->get();

        return response()->json(FollowResource::collection($followings));
    }

    // Obtener los seguidores del usuario autenticado
    public function followers(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        $followers = User::whereIn('id', function ($query) use ($currentUser) {
            $query->select('follower_id')
                ->from('follows')
                ->where('following_id', $currentUser->id);
        })->get();

        return response()->json(FollowResource::collection($followers));
    }
}
