<?php

namespace App\Http\Controllers\API\Social;

use App\Events\UserFollowed;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follow;
use App\Traits\HasUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    use HasUser;

    public function follow(Request $request, $id): JsonResponse
    {
        // Evitar seguirse a uno mismo
        if ($this->getUserId() == $id) {
            return response()->json([
                'message' => 'No puedes seguirte a ti mismo',
                'is_following' => false,
                'status' => null
            ], 400);
        }

        // Cargar usuario con su perfil
        $followedUser = User::with('profile')->find($id);
        if (!$followedUser) {
            return response()->json([
                'message' => 'Usuario no encontrado',
                'is_following' => false,
                'status' => null
            ], 404);
        }

        $follow = Follow::where('follower_id', $this->getUserId())
            ->where('following_id', $id)
            ->first();

        // Usuario ya está siendo seguido o hay una solicitud pendiente
        if ($follow) {
            switch ($follow->status) {
                case 'accepted':
                    return response()->json([
                        'message' => 'Ya sigues a este usuario',
                        'is_following' => true,
                        'status' => 'accepted'
                    ]);

                case 'pending':
                    return response()->json([
                        'message' => 'Ya has enviado una solicitud de seguimiento',
                        'is_following' => false,
                        'status' => 'pending'
                    ]);

                case 'rejected':
                    // Reenviar solicitud si fue rechazada
                    $follow->status = 'pending';
                    $follow->save();
                    event(new UserFollowed($this->authenticatedUser(), $followedUser));
                    return response()->json([
                        'message' => 'Solicitud de seguimiento reenviada',
                        'is_following' => false,
                        'status' => 'pending'
                    ]);

                default:
                    // Estado desconocido, eliminar y crear nueva solicitud
                    $follow->delete();
                    break;
            }
        }

        // Determinar el estado basado en si el perfil es privado
        $isPrivateProfile = $followedUser->private_profile; // Cambio aquí
        $status = $isPrivateProfile ? 'pending' : 'accepted';

        Follow::create([
            'follower_id' => $this->getUserId(),
            'following_id' => $id,
            'status' => $status,
        ]);

        event(new UserFollowed($this->authenticatedUser(), $followedUser));

        $message = $status === 'accepted' ?
            'Ahora sigues a este usuario' :
            'Solicitud de seguimiento enviada';

        return response()->json([
            'message' => $message,
            'is_following' => $status === 'accepted',
            'status' => $status
        ]);
    }

    public function unfollow(Request $request, $id): JsonResponse
    {
        $follow = Follow::where('follower_id', $this->getUserId())
            ->where('following_id', $id)
            ->first();

        if (!$follow) {
            return response()->json([
                'message' => 'No estás siguiendo a este usuario',
                'is_following' => false,
                'status' => null
            ], 404);
        }

        $follow->delete();
        
        return response()->json([
            'message' => 'Has dejado de seguir a este usuario',
            'is_following' => false,
            'status' => null
        ]);
    }

    /**
     * Acepta una solicitud de seguimiento pendiente
     */
    public function acceptFollow(Request $request, $id): JsonResponse
    {
        // Buscamos la solicitud donde el usuario actual es el que recibe la solicitud
        $follow = Follow::where('follower_id', $id)
            ->where('following_id', $this->getUserId())
            ->where('status', 'pending')
            ->first();

        if (!$follow) {
            return response()->json([
                'message' => 'No hay solicitud de seguimiento pendiente de este usuario',
                'is_following' => false,
                'status' => null
            ], 404);
        }

        // Verificar que el estado sea realmente 'pending'
        if ($follow->status !== 'pending') {
            return response()->json([
                'message' => 'La solicitud no está pendiente',
                'is_following' => $follow->status === 'accepted',
                'status' => $follow->status
            ], 400);
        }

        // Actualizamos el estado a aceptado
        $follow->status = 'accepted';
        $follow->save();

        return response()->json([
            'message' => 'Has aceptado la solicitud de seguimiento',
            'is_following' => true,
            'status' => 'accepted'
        ]);
    }

    /**
     * Rechaza una solicitud de seguimiento pendiente
     */
    public function rejectFollow(Request $request, $id): JsonResponse
    {
        // Buscamos la solicitud donde el usuario actual es el que recibe la solicitud
        $follow = Follow::where('follower_id', $id)
            ->where('following_id', $this->getUserId())
            ->where('status', 'pending')
            ->first();

        if (!$follow) {
            return response()->json([
                'message' => 'No hay solicitud de seguimiento pendiente de este usuario',
                'is_following' => false,
                'status' => null
            ], 404);
        }

        // Verificar que el estado sea realmente 'pending'
        if ($follow->status !== 'pending') {
            return response()->json([
                'message' => 'La solicitud no está pendiente',
                'is_following' => $follow->status === 'accepted',
                'status' => $follow->status
            ], 400);
        }

        // Eliminamos la solicitud en lugar de marcarla como rechazada
        $follow->delete();

        return response()->json([
            'message' => 'Has rechazado la solicitud de seguimiento',
            'is_following' => false,
            'status' => null
        ]);
    }

    /**
     * Obtiene las solicitudes de seguimiento pendientes para el usuario autenticado
     */
    public function getPendingRequests(Request $request): JsonResponse
    {
        $pendingRequests = Follow::where('following_id', $this->getUserId())
            ->where('status', 'pending')
            ->with(['follower:id,name,username,profile_picture'])
            ->get()
            ->map(function ($follow) {
                return [
                    'id' => $follow->follower->id,
                    'name' => $follow->follower->name,
                    'username' => $follow->follower->username,
                    'profile_picture' => $follow->follower->profile_picture,
                    'requested_at' => $follow->created_at
                ];
            });

        return response()->json([
            'data' => $pendingRequests,
            'count' => $pendingRequests->count()
        ]);
    }
}
