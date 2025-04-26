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
        $notifications = auth()->user()->notifications()
            ->with(['fromUser', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(NotificationResource::collection($notifications));
    }
    /**
     * Marcar como leída una notificación específica.
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $notification = auth()->user()->notifications()->find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notificación no encontrada.'], 404);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notificación marcada como leída.']);
    }

    /**
     * Marcar todas las notificaciones como leídas.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $notifications = auth()->user()->unreadNotifications;
        $notifications->markAsRead();

        return response()->json(['message' => 'Todas las notificaciones marcadas como leídas.']);
    }
}
