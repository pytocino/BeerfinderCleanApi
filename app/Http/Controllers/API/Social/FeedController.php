<?php

namespace App\Http\Controllers\API\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Log;

class FeedController extends Controller
{
    // Feed general: todos los posts
    public function feedGeneral(Request $request)
    {
        // Obtener página actual, default: 1
        $page = $request->input('page', 1);

        $posts = Post::with([
            'user',
            'beer',
            'likes',      // <--- Cargar todos los likes
            'comments'
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page); // Usar el parámetro page explícitamente

        return PostResource::collection($posts);
    }

    public function feedAmigos(Request $request)
    {
        // Obtener página actual, default: 1
        $page = $request->input('page', 1);

        // Usar auth() en lugar de $request->user()
        $followingIds = auth()->user()->following()->pluck('users.id')->toArray();

        // Si el usuario no sigue a nadie, devolver null
        if (empty($followingIds)) {
            return response()->json(null);
        }

        $posts = Post::whereIn('user_id', $followingIds)
            ->with([
                'user',
                'beer',
                'likes',
                'comments'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        return PostResource::collection($posts);
    }
}
