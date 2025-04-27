<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Obtener todas las notificaciones del usuario autenticado.
     */
    public function getMyNotifications()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return NotificationResource::collection($notifications);
    }

    /**
     * Marcar como leída una notificación específica.
     */
    public function markAsRead(Request $request, $id)
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
    public function markAllAsRead()
    {
        $notifications = auth()->user()->unreadNotifications;

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return response()->json(['message' => 'Todas las notificaciones marcadas como leídas.']);
    }
}
