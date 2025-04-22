<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Obtener todas las notificaciones del usuario autenticado.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = Notification::where('user_id', $user->id)
            ->with('fromUser')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($notifications);
    }

    /**
     * Mostrar una notificación específica.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->with('fromUser')
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notificación no encontrada.'], 404);
        }

        return response()->json($notification);
    }

    /**
     * Crear una nueva notificación.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'from_user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'related_id' => 'required|integer',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'from_user_id' => $request->from_user_id,
            'type' => $request->type,
            'related_id' => $request->related_id,
            'is_read' => false,
        ]);

        return response()->json($notification, 201);
    }

    /**
     * Actualizar una notificación específica.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notificación no encontrada.'], 404);
        }

        $request->validate([
            'is_read' => 'boolean',
        ]);

        $notification->update($request->only(['is_read']));

        if ($request->input('is_read') === true && !$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
        }

        return response()->json($notification);
    }

    /**
     * Eliminar una notificación específica.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notificación no encontrada.'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notificación eliminada correctamente.']);
    }

    /**
     * Marcar como leída una notificación específica.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notificación no encontrada.'], 404);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notificación marcada como leída.']);
    }

    /**
     * Marcar todas las notificaciones como leídas.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['message' => 'Todas las notificaciones marcadas como leídas.']);
    }
}
