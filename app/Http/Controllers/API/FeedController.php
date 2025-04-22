<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Http\Resources\PostResource;

class FeedController extends Controller
{
    // Feed general: todos los posts
    public function feedGeneral()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return PostResource::collection($posts);
    }

    // Feed de amigos: posts de los usuarios que sigue el usuario autenticado
    public function feedAmigos(Request $request)
    {
        $user = $request->user();

        // Suponiendo que tienes una relación 'following' en el modelo User
        $followingIds = $user->following()->pluck('id');
        $posts = Post::whereIn('user_id', $followingIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return PostResource::collection($posts);
    }
}
