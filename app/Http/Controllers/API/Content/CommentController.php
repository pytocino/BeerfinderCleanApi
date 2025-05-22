<?php

namespace App\Http\Controllers\API\Content;

use App\Events\PostCommented;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    /**
     * Mostrar todos los comentarios de un post específico.
     */
    public function index(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post no encontrado.'], 404);
        }

        // Consulta correcta: comentarios raíz del post, con usuario y replies con usuario
        $comments = Comment::where('post_id', '=', $post->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();



        return response()->json(CommentResource::collection($comments));
    }

    /**
     * Mostrar un comentario específico.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $comment = Comment::with(['user', 'replies.user'])->find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comentario no encontrado.'], 404);
        }

        return response()->json(new CommentResource($comment));
    }

    /**
     * Almacenar un nuevo comentario para un post específico.
     */
    public function store(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post no encontrado.'], 404);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = new Comment();
        $comment->user_id = $request->user()->id;
        $comment->post_id = $post->id;
        $comment->content = $validated['content'];
        $comment->parent_id = $validated['parent_id'] ?? null;
        $comment->save();

        event(new PostCommented($request->user(), $post, $comment));

        return response()->json(new CommentResource($comment), 201);
    }

    /**
     * Actualizar un comentario específico.
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

        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment->content = $validated['content'];
        $comment->edited = true;
        $comment->edited_at = now();
        $comment->save();

        return response()->json(new CommentResource($comment));
    }

    /**
     * Eliminar un comentario específico.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comentario no encontrado.'], 404);
        }

        // Verificar si el usuario es el dueño del comentario o el dueño del post
        $isOwner = $comment->user_id === $request->user()->id;
        $isPostOwner = Post::where('id', '=', $comment->post_id)
            ->where('user_id', '=', $request->user()->id)
            ->exists();

        if (!$isOwner && !$isPostOwner && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'No tienes permiso para eliminar este comentario.'], 403);
        }

        // Decrementar el contador de comentarios del post
        Post::where('id', '=', $comment->post_id)->decrement('comments_count');

        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado correctamente.']);
    }

    /**
     * Devuelve todos los comentarios de un post específico como recursos CommentResource.
     */
    public function getPostComments(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post no encontrado.'], 404);
        }

        $comments = Comment::where('post_id', '=', $post->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(CommentResource::collection($comments));
    }
}
