<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Traits\HasUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use HasUser;

    /**
     * Obtiene todas las notificaciones del usuario autenticado
     */
    public function index(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();
        
        $perPage = $request->input('per_page', 15);
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'unread_count' => $user->unreadNotifications()->count()
            ]
        ]);
    }

    /**
     * Obtiene solo las notificaciones no leídas
     */
    public function unread(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();
        
        $perPage = $request->input('per_page', 15);
        
        $notifications = $user->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total()
            ]
        ]);
    }

    /**
     * Marca una notificación específica como leída
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = $this->authenticatedUser();
        
        $notification = $user->notifications()->find($id);
        
        if (!$notification) {
            return response()->json([
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notificación marcada como leída'
        ]);
    }

    /**
     * Marca todas las notificaciones como leídas
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();
        
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'Todas las notificaciones han sido marcadas como leídas'
        ]);
    }

    /**
     * Elimina una notificación específica
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = $this->authenticatedUser();
        
        $notification = $user->notifications()->find($id);
        
        if (!$notification) {
            return response()->json([
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notificación eliminada'
        ]);
    }

    /**
     * Elimina todas las notificaciones leídas
     */
    public function clearRead(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();
        
        $deletedCount = $user->readNotifications()->delete();

        return response()->json([
            'message' => "Se eliminaron {$deletedCount} notificaciones leídas"
        ]);
    }

    /**
     * Obtiene el conteo de notificaciones no leídas
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();
        
        $count = $user->unreadNotifications()->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }
}
