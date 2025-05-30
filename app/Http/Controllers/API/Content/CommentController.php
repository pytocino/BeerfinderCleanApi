<?php

namespace App\Http\Controllers\API\Content;

use App\Events\PostCommented;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CommentResource;
use App\Traits\HasUser;

class CommentController extends Controller
{
    use HasUser;

    /**
     * Almacenar un nuevo comentario para un post específico.
     */
    public function store(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        $validated = $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:user_comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => $this->getUserId(),
            'post_id' => $post->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'edited' => false,
            'pinned' => false,
            'likes_count' => 0,
        ]);

        // Cargar relaciones para la respuesta
        $comment->load(['user']);

        // TODO: Revisar problema con evento PostCommented
        // event(new PostCommented($this->authenticatedUser(), $post, $comment));

        return response()->json([
            'message' => 'Comentario creado',
            'data' => new CommentResource($comment)
        ], 201);
    }

    /**
     * Actualizar un comentario específico.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        // Actualizar directamente en lugar de usar editContent
        $comment->update([
            'content' => $validated['content'],
            'edited' => true,
            'edited_at' => now(),
        ]);

        // Cargar relaciones para la respuesta
        $comment->load(['user', 'likes']);

        return response()->json([
            'message' => 'Comentario actualizado exitosamente',
            'data' => new CommentResource($comment)
        ]);
    }

    /**
     * Eliminar un comentario específico.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado correctamente']);
    }

    /**
     * Devuelve todos los comentarios de un post específico como recursos CommentResource.
     */
    public function getPostComments(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        $comments = Comment::where('post_id', $post->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'likes'])
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Comentarios del post obtenidos exitosamente',
            'data' => CommentResource::collection($comments)
        ]);
    }

    // =====================================================
    // MÉTODOS DE LIKES
    // =====================================================

    /**
     * Alternar like en un comentario.
     */
    public function toggleLike(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        $wasAdded = $comment->toggleLike($this->getUserId());
        
        // Cargar relaciones actualizadas
        $comment->load(['user', 'likes']);

        $message = $wasAdded ? 'Like agregado exitosamente' : 'Like removido exitosamente';
        
        return response()->json([
            'message' => $message,
            'data' => [
                'comment' => new CommentResource($comment),
                'liked' => $wasAdded,
                'likes_count' => $comment->likes_count
            ]
        ]);
    }

    // =====================================================
    // MÉTODOS DE ADMINISTRACIÓN
    // =====================================================

    /**
     * Fijar o desfijar un comentario (solo propietario del post o admin).
     */
    public function togglePin(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        $newPinnedState = !$comment->isPinned();
        $comment->setPinned($newPinnedState);

        $comment->load(['user', 'likes']);

        $message = $newPinnedState ? 'Comentario fijado exitosamente' : 'Comentario desfijado exitosamente';

        return response()->json([
            'message' => $message,
            'data' => [
                'comment' => new CommentResource($comment),
                'pinned' => $newPinnedState
            ]
        ]);
    }
}
