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
use App\Traits\ApiResponser;
use App\Traits\HasUser;

class CommentController extends Controller
{
    use ApiResponser, HasUser;
    /**
     * Mostrar todos los comentarios de un post específico.
     */
    public function index(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->errorResponse('Post no encontrado', 404);
        }

        // Incluir likes y información más completa
        $comments = Comment::where('post_id', $post->id)
            ->whereNull('parent_id')
            ->with([
                'user', 
                'replies.user', 
                'replies.likes',
                'likes'
            ])
            ->orderBy('pinned', 'desc')  // Comentarios fijados primero
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse(CommentResource::collection($comments), 'Comentarios obtenidos exitosamente');
    }

    /**
     * Mostrar un comentario específico.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $comment = Comment::with(['user', 'replies.user', 'likes'])->find($id);

        if (!$comment) {
            return $this->errorResponse('Comentario no encontrado', 404);
        }

        return $this->successResponse(new CommentResource($comment), 'Comentario obtenido exitosamente');
    }

    /**
     * Almacenar un nuevo comentario para un post específico.
     */
    public function store(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->errorResponse('Post no encontrado', 404);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:user_comments,id',
        ]);

        // Verificar que el parent_id pertenece al mismo post si se especifica
        if ($validated['parent_id']) {
            $parentComment = Comment::find($validated['parent_id']);
            if ($parentComment->post_id !== $post->id) {
                return $this->errorResponse('El comentario padre no pertenece a este post', 400);
            }
        }

        $comment = Comment::create([
            'user_id' => $this->getUserId(),
            'post_id' => $post->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Incrementar contador de comentarios del post
        $post->increment('comments_count');

        // Cargar relaciones para la respuesta
        $comment->load(['user']);

        // TODO: Revisar problema con evento PostCommented
        // event(new PostCommented($this->authenticatedUser(), $post, $comment));

        return $this->successResponse(new CommentResource($comment), 'Comentario creado exitosamente', 201);
    }

    /**
     * Actualizar un comentario específico.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return $this->errorResponse('Comentario no encontrado', 404);
        }

        if ($comment->user_id !== $this->getUserId()) {
            return $this->errorResponse('No tienes permiso para editar este comentario', 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        // Usar el método del modelo para editar
        $comment->editContent($validated['content']);

        // Cargar relaciones para la respuesta
        $comment->load(['user', 'likes']);

        return $this->successResponse(new CommentResource($comment), 'Comentario actualizado exitosamente');
    }

    /**
     * Eliminar un comentario específico.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return $this->errorResponse('Comentario no encontrado', 404);
        }

        // Verificar permisos usando métodos del modelo
        $isOwner = $comment->user_id === $this->getUserId();
        $isPostOwner = $comment->post->user_id === $this->getUserId();

        if (!$isOwner && !$isPostOwner && !$this->authenticatedUser()->isAdmin()) {
            return $this->errorResponse('No tienes permiso para eliminar este comentario', 403);
        }

        $comment->delete();

        return $this->successResponse(null, 'Comentario eliminado correctamente');
    }

    /**
     * Devuelve todos los comentarios de un post específico como recursos CommentResource.
     */
    public function getPostComments(Request $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->errorResponse('Post no encontrado', 404);
        }

        $comments = Comment::where('post_id', $post->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'likes'])
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse(CommentResource::collection($comments), 'Comentarios del post obtenidos exitosamente');
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

        if (!$comment) {
            return $this->errorResponse('Comentario no encontrado', 404);
        }

        $wasAdded = $comment->toggleLike($this->getUserId());
        
        // Cargar relaciones actualizadas
        $comment->load(['user', 'likes']);

        $message = $wasAdded ? 'Like agregado exitosamente' : 'Like removido exitosamente';
        
        return $this->successResponse([
            'comment' => new CommentResource($comment),
            'liked' => $wasAdded,
            'likes_count' => $comment->likes_count
        ], $message);
    }

    /**
     * Verificar si el usuario actual dio like al comentario.
     */
    public function checkLike(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return $this->errorResponse('Comentario no encontrado', 404);
        }

        $isLiked = $comment->isLikedByUser($this->getUserId());

        return $this->successResponse([
            'liked' => $isLiked,
            'likes_count' => $comment->likes_count
        ], 'Estado de like verificado');
    }

    /**
     * Obtener usuarios que dieron like al comentario.
     */
    public function getLikers(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return $this->errorResponse('Comentario no encontrado', 404);
        }

        $likers = $comment->getUsersWhoLiked();

        return $this->successResponse($likers, 'Usuarios que dieron like obtenidos exitosamente');
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

        if (!$comment) {
            return $this->errorResponse('Comentario no encontrado', 404);
        }

        // Verificar permisos: propietario del post o admin
        $isPostOwner = $comment->post->user_id === $this->getUserId();
        if (!$isPostOwner && !$this->authenticatedUser()->isAdmin()) {
            return $this->errorResponse('No tienes permiso para fijar comentarios en este post', 403);
        }

        $newPinnedState = !$comment->isPinned();
        $comment->setPinned($newPinnedState);

        $comment->load(['user', 'likes']);

        $message = $newPinnedState ? 'Comentario fijado exitosamente' : 'Comentario desfijado exitosamente';

        return $this->successResponse([
            'comment' => new CommentResource($comment),
            'pinned' => $newPinnedState
        ], $message);
    }

    /**
     * Obtener estadísticas del comentario.
     */
    public function getStats(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return $this->errorResponse('Comentario no encontrado', 404);
        }

        $stats = [
            'likes_count' => $comment->getLikesCount(),
            'replies_count' => $comment->getRepliesCount(),
            'is_edited' => $comment->hasBeenEdited(),
            'is_pinned' => $comment->isPinned(),
            'is_reply' => $comment->isReply(),
            'has_replies' => $comment->hasReplies(),
            'created_at' => $comment->created_at,
            'edited_at' => $comment->edited_at
        ];

        return $this->successResponse($stats, 'Estadísticas del comentario obtenidas exitosamente');
    }
}
