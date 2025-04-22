<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * Mostrar todos los comentarios de un post específico.
     *
     * @param Request $request
     * @param int $id ID del post
     * @return JsonResponse
     */
    public function index(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post no encontrado.'], 404);
        }

        $comments = $post->comments()->with('user')->get();
        return response()->json($comments);
    }

    /**
     * Mostrar un comentario específico.
     *
     * @param Request $request
     * @param int $id ID del comentario
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        $comment = Comment::with(['user', 'replies.user'])->find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comentario no encontrado.'], 404);
        }

        return response()->json($comment);
    }

    /**
     * Almacenar un nuevo comentario para un post específico.
     *
     * @param Request $request
     * @param int $id ID del post
     * @return JsonResponse
     */
    public function store(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post no encontrado.'], 404);
        }

        $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = new Comment();
        $comment->user_id = $request->user()->id;
        $comment->post_id = $post->id;
        $comment->content = $request->input('content');
        $comment->parent_id = $request->input('parent_id');
        $comment->save();

        // Incrementar el contador de comentarios del post
        $post->increment('comments_count');

        return response()->json($comment, 201);
    }

    /**
     * Actualizar un comentario específico.
     *
     * @param Request $request
     * @param int $id ID del comentario
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comentario no encontrado.'], 404);
        }

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para editar este comentario.'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment->content = $request->input('content');
        $comment->edited = true;
        $comment->edited_at = now();
        $comment->save();

        return response()->json($comment);
    }

    /**
     * Eliminar un comentario específico.
     *
     * @param Request $request
     * @param int $id ID del comentario
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comentario no encontrado.'], 404);
        }

        // Verificar si el usuario es el dueño del comentario o el dueño del post
        $isOwner = $comment->user_id === $request->user()->id;
        $isPostOwner = Post::where('id', $comment->post_id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if (!$isOwner && !$isPostOwner && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'No tienes permiso para eliminar este comentario.'], 403);
        }

        // Decrementar el contador de comentarios del post
        Post::where('id', $comment->post_id)->decrement('comments_count');

        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado correctamente.']);
    }
}
