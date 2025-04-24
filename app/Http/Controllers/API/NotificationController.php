<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    /**
     * Obtener todas las notificaciones del usuario autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->with(['fromUser', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json(NotificationResource::collection($notifications));
    }

    /**
     * Mostrar una notificación específica.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = auth()->user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['fromUser', 'user'])
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notificación no encontrada.'], 404);
        }

        return response()->json(new NotificationResource($notification));
    }

    /**
     * Crear una nueva notificación.
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

        return response()->json(new NotificationResource($notification), 201);
    }

    /**
     * Actualizar una notificación específica.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = auth()->user();
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

        return response()->json(new NotificationResource($notification));
    }

    /**
     * Eliminar una notificación específica.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = auth()->user();
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
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $user = auth()->user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notificación no encontrada.'], 404);
        }

        $notification->is_read = true;
        if (!$notification->read_at) {
            $notification->read_at = now();
        }
        $notification->save();

        return response()->json(['message' => 'Notificación marcada como leída.']);
    }

    /**
     * Marcar todas las notificaciones como leídas.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = auth()->user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['message' => 'Todas las notificaciones marcadas como leídas.']);
    }
}
