<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @group Notificaciones
 *
 * APIs para gestionar las notificaciones del usuario
 */
class NotificationController extends Controller
{
    /**
     * Listar notificaciones
     *
     * Obtiene un listado paginado de las notificaciones del usuario autenticado.
     *
     * @authenticated
     *
     * @queryParam read boolean Filtrar por notificaciones leídas (true) o no leídas (false). Example: false
     * @queryParam type string Filtrar por tipo (follow, like, comment, check_in, report). Example: like
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     * @queryParam page integer Número de página. Example: 1
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 102,
     *      "type": "like",
     *      "from_user": {
     *        "id": 7,
     *        "name": "María López",
     *        "profile_picture": "https://example.com/avatars/maria.jpg"
     *      },
     *      "data": {
     *        "check_in_id": 42,
     *        "beer_name": "Founders Breakfast Stout"
     *      },
     *      "read": false,
     *      "created_at": "2023-04-19T15:30:00.000000Z"
     *    },
     *    {
     *      "id": 98,
     *      "type": "comment",
     *      "from_user": {
     *        "id": 12,
     *        "name": "Carlos Gómez",
     *        "profile_picture": "https://example.com/avatars/carlos.jpg"
     *      },
     *      "data": {
     *        "check_in_id": 42,
     *        "beer_name": "Founders Breakfast Stout",
     *        "comment_preview": "Totalmente de acuerdo, una maravilla de cerveza."
     *      },
     *      "read": true,
     *      "created_at": "2023-04-18T19:15:00.000000Z"
     *    }
     *  ],
     *  "unread_count": 3,
     *  "links": {...},
     *  "meta": {...}
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'is_read' => 'nullable|boolean',
            'type' => 'nullable|string|in:follow,like,comment,check_in,report',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $query = Notification::where('user_id', $request->user()->id)
            ->with('fromUser:id,name,profile_picture');

        // Aplicar filtros
        if (isset($validated['is_read'])) {
            $query->where('is_read', $validated['is_read']);
        }

        if (!empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        // Ordenar por más recientes primero
        $query->orderByDesc('created_at');

        $perPage = $validated['per_page'] ?? 15;

        $notifications = $query->paginate($perPage);

        // Obtener el conteo de notificaciones no leídas
        $unreadCount = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        // Preparar la colección de recursos con transformación personalizada
        $collection = $notifications->map(function ($notification) {
            return $this->transformNotification($notification);
        });

        // CORRECCIÓN: Usar el array de paginación correctamente
        $paginationArray = $notifications->toArray();

        return response()->json([
            'data' => $collection,
            'unread_count' => $unreadCount,
            'links' => $paginationArray['links'] ?? [],
            'meta' => [
                'current_page' => $paginationArray['current_page'] ?? 1,
                'from' => $paginationArray['from'] ?? null,
                'last_page' => $paginationArray['last_page'] ?? 1,
                'path' => $paginationArray['path'] ?? '',
                'per_page' => $paginationArray['per_page'] ?? $perPage,
                'to' => $paginationArray['to'] ?? null,
                'total' => $paginationArray['total'] ?? count($collection),
            ]
        ]);
    }

    /**
     * Marcar notificación como leída
     *
     * Marca una notificación específica como leída.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la notificación. Example: 102
     *
     * @response {
     *  "message": "La notificación ha sido marcada como leída.",
     *  "unread_count": 2
     * }
     *
     * @response 403 {
     *  "message": "No tienes permiso para acceder a esta notificación."
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la notificación solicitada."
     * }
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        try {
            $notification = Notification::findOrFail($id);

            // Verificar que la notificación pertenece al usuario autenticado
            if ($notification->user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'No tienes permiso para acceder a esta notificación.'
                ], 403);
            }

            // Marcar como leída solo si no lo estaba ya
            if (!$notification->is_read) {
                // Usar el método del modelo para marcar como leída
                $notification->markAsRead();
            }

            // Obtener el conteo actualizado de notificaciones no leídas
            $unreadCount = Notification::where('user_id', $request->user()->id)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'message' => 'La notificación ha sido marcada como leída.',
                'unread_count' => $unreadCount
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No se ha encontrado la notificación solicitada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al procesar la solicitud.'
            ], 500);
        }
    }

    /**
     * Marcar todas las notificaciones como leídas
     *
     * Marca todas las notificaciones no leídas del usuario como leídas.
     *
     * @authenticated
     *
     * @response {
     *  "message": "Todas las notificaciones han sido marcadas como leídas.",
     *  "count_updated": 3
     * }
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        // Obtener todas las notificaciones no leídas del usuario
        $unreadNotifications = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->get();

        $countUpdated = $unreadNotifications->count();

        if ($countUpdated > 0) {
            // Actualizar en lote para optimizar rendimiento
            Notification::where('user_id', $request->user()->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json([
            'message' => 'Todas las notificaciones han sido marcadas como leídas.',
            'count_updated' => $countUpdated
        ]);
    }

    /**
     * Eliminar notificación
     *
     * Elimina una notificación específica.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la notificación. Example: 102
     *
     * @response {
     *  "message": "La notificación ha sido eliminada."
     * }
     *
     * @response 403 {
     *  "message": "No tienes permiso para eliminar esta notificación."
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la notificación solicitada."
     * }
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $notification = Notification::findOrFail($id);

            // Verificar que la notificación pertenece al usuario autenticado
            if ($notification->user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'No tienes permiso para eliminar esta notificación.'
                ], 403);
            }

            $notification->delete();

            return response()->json([
                'message' => 'La notificación ha sido eliminada.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No se ha encontrado la notificación solicitada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al procesar la solicitud.'
            ], 500);
        }
    }

    /**
     * Eliminar todas las notificaciones leídas
     *
     * Elimina todas las notificaciones ya leídas del usuario.
     *
     * @authenticated
     *
     * @response {
     *  "message": "Todas las notificaciones leídas han sido eliminadas.",
     *  "count_deleted": 15
     * }
     */
    public function clearReadNotifications(Request $request): JsonResponse
    {
        // Contar cuántas notificaciones leídas tiene el usuario
        $readNotificationsCount = Notification::where('user_id', $request->user()->id)
            ->where('is_read', true)
            ->count();

        if ($readNotificationsCount > 0) {
            // Eliminar todas las notificaciones leídas del usuario
            Notification::where('user_id', $request->user()->id)
                ->where('is_read', true)
                ->delete();
        }

        return response()->json([
            'message' => 'Todas las notificaciones leídas han sido eliminadas.',
            'count_deleted' => $readNotificationsCount
        ]);
    }

    /**
     * Obtener el conteo de notificaciones no leídas
     *
     * Devuelve el número de notificaciones no leídas para el usuario autenticado.
     *
     * @authenticated
     *
     * @response {
     *  "unread_count": 3
     * }
     */
    public function getUnreadCount(Request $request): JsonResponse
    {
        $unreadCount = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Transforma la notificación en una estructura legible
     */
    private function transformNotification(Notification $notification): array
    {
        $transformedNotification = [
            'id' => $notification->id,
            'type' => $notification->type,
            'from_user' => $notification->fromUser ? [
                'id' => $notification->fromUser->id,
                'name' => $notification->fromUser->name,
                'profile_picture' => $notification->fromUser->profile_picture,
            ] : null,
            'data' => $notification->data, // Ya está convertido por el cast en el modelo
            'is_read' => (bool)$notification->is_read,
            'created_at' => $notification->created_at,
        ];

        // Personalizar el mensaje según el tipo
        switch ($notification->type) {
            case 'follow':
                $transformedNotification['message'] = "{$notification->fromUser->name} ha comenzado a seguirte.";
                break;

            case 'like':
                $data = $notification->data;
                $beerName = $data['beer_name'] ?? 'una cerveza';
                $transformedNotification['message'] = "{$notification->fromUser->name} ha dado like a tu check-in de {$beerName}.";
                break;

            case 'comment':
                $data = $notification->data;
                $beerName = $data['beer_name'] ?? 'una cerveza';
                $transformedNotification['message'] = "{$notification->fromUser->name} ha comentado en tu check-in de {$beerName}.";
                break;

            case 'check_in':
                $data = $notification->data;
                $beerName = $data['beer_name'] ?? 'una cerveza';
                $rating = isset($data['rating']) ? "con una puntuación de {$data['rating']}" : "";
                $transformedNotification['message'] = "{$notification->fromUser->name} ha hecho check-in de {$beerName} {$rating}.";
                break;

            case 'report':
                $transformedNotification['message'] = "Se ha reportado contenido que requiere tu atención.";
                break;

            default:
                $transformedNotification['message'] = "Tienes una nueva notificación.";
                break;
        }

        return $transformedNotification;
    }
}
