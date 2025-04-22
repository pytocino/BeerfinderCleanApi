<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\CheckIn;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @group Comentarios
 *
 * APIs para gestionar comentarios en los check-ins de cervezas
 */
class CommentController extends Controller
{
    /**
     * Listar comentarios
     *
     * Obtiene los comentarios de un check-in específico.
     *
     * @urlParam id integer required ID del check-in. Example: 42
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 105,
     *      "user": {
     *        "id": 7,
     *        "name": "María López",
     *        "profile_picture": "https://example.com/avatars/maria.jpg"
     *      },
     *      "content": "Totalmente de acuerdo, una maravilla de cerveza.",
     *      "created_at": "2023-04-18T19:15:00.000000Z",
     *      "updated_at": "2023-04-18T19:15:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el check-in solicitado."
     * }
     */
    public function getCheckInComments(Request $request, $checkInId): JsonResponse
    {
        try {
            // Verificar que el check-in existe
            $checkIn = CheckIn::findOrFail($checkInId);

            $validated = $request->validate([
                'per_page' => 'nullable|integer|min:5|max:50',
                'sort' => 'nullable|string|in:created_at,updated_at',
                'order' => 'nullable|string|in:asc,desc',
            ]);

            $perPage = $validated['per_page'] ?? 10;
            $sort = $validated['sort'] ?? 'created_at';
            $order = $validated['order'] ?? 'asc'; // Mostrar primero los más antiguos por defecto

            $comments = Comment::where('check_in_id', $checkInId)
                ->with('user:id,name,profile_picture')
                ->orderBy($sort, $order)
                ->paginate($perPage);

            $paginationArray = $comments->toArray();

            return response()->json([
                'data' => CommentResource::collection($comments),
                'links' => $paginationArray['links'] ?? [],
                'meta' => [
                    'current_page' => $paginationArray['current_page'] ?? 1,
                    'from' => $paginationArray['from'] ?? null,
                    'last_page' => $paginationArray['last_page'] ?? 1,
                    'path' => $paginationArray['path'] ?? '',
                    'per_page' => $paginationArray['per_page'] ?? $perPage,
                    'to' => $paginationArray['to'] ?? null,
                    'total' => $paginationArray['total'] ?? count($comments),
                    'check_in_id' => (int)$checkInId
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el check-in solicitado.'], 404);
        }
    }

    /**
     * Crear comentario
     *
     * Añade un nuevo comentario a un check-in.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del check-in. Example: 42
     * @bodyParam content string required Contenido del comentario. Example: Totalmente de acuerdo, una maravilla de cerveza.
     *
     * @response 201 {
     *  "data": {
     *    "id": 105,
     *    "user": {
     *      "id": 7,
     *      "name": "María López",
     *      "profile_picture": "https://example.com/avatars/maria.jpg"
     *    },
     *    "content": "Totalmente de acuerdo, una maravilla de cerveza.",
     *    "created_at": "2023-04-18T19:15:00.000000Z",
     *    "updated_at": "2023-04-18T19:15:00.000000Z"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el check-in solicitado."
     * }
     * 
     * @response 422 {
     *  "message": "El comentario no puede estar vacío.",
     *  "errors": {
     *    "content": ["El contenido es obligatorio."]
     *  }
     * }
     */
    public function store(Request $request, $checkInId): JsonResponse
    {
        try {
            // Verificar que el check-in existe
            $checkIn = CheckIn::with('user')->findOrFail($checkInId);

            $validated = $request->validate([
                'content' => 'required|string|max:500',
            ]);

            // Crear el comentario
            $comment = Comment::create([
                'user_id' => $request->user()->id,
                'check_in_id' => $checkInId,
                'content' => $validated['content'],
            ]);

            // Cargar relación de usuario para la respuesta
            $comment->load('user:id,name,profile_picture');

            // Crear notificación para el dueño del check-in si no es el mismo usuario
            if ($request->user()->id !== $checkIn->user_id) {
                Notification::create([
                    'user_id' => $checkIn->user_id,
                    'type' => 'comment',
                    'from_user_id' => $request->user()->id,
                    'data' => json_encode([
                        'check_in_id' => $checkInId,
                        'beer_name' => $checkIn->beer->name ?? 'una cerveza',
                        'comment_preview' => mb_substr($validated['content'], 0, 50) . (strlen($validated['content']) > 50 ? '...' : '')
                    ]),
                    'read' => false
                ]);
            }

            return response()->json(['data' => new CommentResource($comment)], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'El comentario no puede estar vacío.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el check-in solicitado.'], 404);
        }
    }

    /**
     * Actualizar comentario
     *
     * Actualiza el contenido de un comentario existente.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del comentario. Example: 105
     * @bodyParam content string required Nuevo contenido del comentario. Example: He vuelto a probarla y sigue siendo excelente.
     *
     * @response {
     *  "data": {
     *    "id": 105,
     *    "user": {
     *      "id": 7,
     *      "name": "María López",
     *      "profile_picture": "https://example.com/avatars/maria.jpg"
     *    },
     *    "content": "He vuelto a probarla y sigue siendo excelente.",
     *    "created_at": "2023-04-18T19:15:00.000000Z",
     *    "updated_at": "2023-04-18T19:45:00.000000Z"
     *  }
     * }
     *
     * @response 403 {
     *  "message": "No tienes permiso para editar este comentario."
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el comentario solicitado."
     * }
     *
     * @response 422 {
     *  "message": "El comentario no puede estar vacío.",
     *  "errors": {
     *    "content": ["El contenido es obligatorio."]
     *  }
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $comment = Comment::with('user:id,name,profile_picture')->findOrFail($id);

            // Verificar permisos (solo el autor o un administrador)
            if ($request->user()->id !== $comment->user_id && !$request->user()->is_admin) {
                return response()->json(['message' => 'No tienes permiso para editar este comentario.'], 403);
            }

            $validated = $request->validate([
                'content' => 'required|string|max:500',
            ]);

            $comment->update([
                'content' => $validated['content'],
            ]);

            return response()->json(['data' => new CommentResource($comment)]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'El comentario no puede estar vacío.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el comentario solicitado.'], 404);
        }
    }

    /**
     * Eliminar comentario
     *
     * Elimina un comentario del sistema.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del comentario. Example: 105
     *
     * @response 204 {}
     *
     * @response 403 {
     *  "message": "No tienes permiso para eliminar este comentario."
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el comentario solicitado."
     * }
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $comment = Comment::findOrFail($id);
            $checkIn = CheckIn::findOrFail($comment->check_in_id);

            // Verificar permisos (autor del comentario, dueño del check-in o administrador)
            if (
                $request->user()->id !== $comment->user_id &&
                $request->user()->id !== $checkIn->user_id &&
                !$request->user()->is_admin
            ) {
                return response()->json(['message' => 'No tienes permiso para eliminar este comentario.'], 403);
            }

            // Eliminar notificaciones relacionadas con este comentario
            Notification::where('type', 'comment')
                ->where('from_user_id', $comment->user_id)
                ->whereRaw('JSON_EXTRACT(data, "$.check_in_id") = ?', [$comment->check_in_id])
                ->delete();

            $comment->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el comentario solicitado.'], 404);
        }
    }

    /**
     * Reportar comentario
     *
     * Reporta un comentario inapropiado para revisión por los moderadores.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del comentario. Example: 105
     * @bodyParam reason string required Motivo del reporte (spam, offensive, inappropriate). Example: offensive
     * @bodyParam details string Detalles adicionales sobre el reporte. Example: Contiene lenguaje ofensivo e insultos.
     *
     * @response {
     *  "message": "El comentario ha sido reportado y será revisado por los moderadores.",
     *  "report_id": 42
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el comentario solicitado."
     * }
     *
     * @response 422 {
     *  "message": "Los datos del reporte no son válidos.",
     *  "errors": {
     *    "reason": ["El motivo del reporte es obligatorio."]
     *  }
     * }
     */
    public function report(Request $request, $id): JsonResponse
    {
        try {
            $comment = Comment::findOrFail($id);

            $validated = $request->validate([
                'reason' => 'required|string|in:spam,offensive,inappropriate',
                'details' => 'nullable|string|max:500',
            ]);

            // Crear un reporte en la base de datos
            $report = \App\Models\Report::create([
                'user_id' => $request->user()->id,
                'reportable_type' => 'comment',
                'reportable_id' => $id,
                'reason' => $validated['reason'],
                'details' => $validated['details'] ?? null,
                'status' => 'pending'
            ]);

            // Notificar a los administradores
            $admins = \App\Models\User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'report',
                    'from_user_id' => $request->user()->id,
                    'data' => json_encode([
                        'report_id' => $report->id,
                        'report_type' => 'comment',
                        'reported_content' => mb_substr($comment->content, 0, 50) . (strlen($comment->content) > 50 ? '...' : '')
                    ]),
                    'read' => false
                ]);
            }

            return response()->json([
                'message' => 'El comentario ha sido reportado y será revisado por los moderadores.',
                'report_id' => $report->id
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos del reporte no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el comentario solicitado.'], 404);
        }
    }
}
