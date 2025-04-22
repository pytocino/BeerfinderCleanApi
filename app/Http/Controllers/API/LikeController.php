<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    /**
     * Obtener todos los likes de un post específico.
     *
     * @param Request $request
     * @param int $id ID del post
     * @return JsonResponse
     */
    public function getPostLikes(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post no encontrado.'], 404);
        }

        $likes = Like::with('user')
            ->where('post_id', $id)
            ->get();

        return response()->json($likes);
    }

    /**
     * Dar like a un post específico.
     *
     * @param Request $request
     * @param int $id ID del post
     * @return JsonResponse
     */
    public function likePost(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post no encontrado.'], 404);
        }

        if (Like::isLiked($user->id, $id)) {
            return response()->json(['message' => 'Ya has dado like a este post.'], 409);
        }

        $like = Like::create([
            'user_id' => $user->id,
            'post_id' => $id,
            'liked_at' => now(),
        ]);

        // Incrementar el contador de likes del post
        $post->increment('likes_count');

        return response()->json($like, 201);
    }

    /**
     * Quitar like a un post específico.
     *
     * @param Request $request
     * @param int $id ID del post
     * @return JsonResponse
     */
    public function unlikePost(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post no encontrado.'], 404);
        }

        $deleted = Like::where('user_id', $user->id)
            ->where('post_id', $id)
            ->delete();

        if (!$deleted) {
            return response()->json(['message' => 'No has dado like a este post.'], 404);
        }

        // Decrementar el contador de likes del post
        $post->decrement('likes_count');

        return response()->json(['message' => 'Like eliminado correctamente.']);
    }
}
