<?php

namespace App\Http\Controllers\API\Social;

use App\Events\UserFollowed;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function follow(Request $request, $id): JsonResponse
    {
        // Evitar seguirse a uno mismo
        if (auth()->id() == $id) {
            return $this->createResponse('No puedes seguirte a ti mismo', false, null, 400);
        }

        // Cargar usuario con su perfil
        $followedUser = User::with('profile')->find($id);
        if (!$followedUser) {
            return $this->createResponse('Usuario no encontrado', false, null, 404);
        }

        $follow = Follow::where('follower_id', '=', auth()->id())
            ->where('following_id', '=', $id)
            ->first();

        // Usuario ya está siendo seguido o hay una solicitud pendiente
        if ($follow) {
            switch ($follow->status) {
                case 'accepted':
                    return $this->createResponse('Ya sigues a este usuario', true, 'accepted', 200);

                case 'pending':
                    return $this->createResponse('Ya has enviado una solicitud de seguimiento', false, 'pending', 200);

                case 'rejected':
                    // Reenviar solicitud si fue rechazada
                    $follow->status = 'pending';
                    $follow->save();
                    event(new UserFollowed(auth()->user(), $followedUser));
                    return $this->createResponse('Solicitud de seguimiento reenviada', false, 'pending', 200);
            }
        }

        // Determinar el estado basado en si el perfil es privado
        $isPrivateProfile = $followedUser->profile && $followedUser->profile->private_profile;
        $status = $isPrivateProfile ? 'pending' : 'accepted';

        Follow::create([
            'follower_id' => auth()->id(),
            'following_id' => $id,
            'status' => $status,
        ]);

        event(new UserFollowed(auth()->user(), $followedUser));

        $message = $status === 'accepted' ?
            'Ahora sigues a este usuario' :
            'Solicitud de seguimiento enviada';

        return $this->createResponse($message, $status === 'accepted', $status, 200);
    }

    public function unfollow(Request $request, $id): JsonResponse
    {
        $follow = Follow::where('follower_id', '=', auth()->id())
            ->where('following_id', '=',     $id)
            ->first();

        if (!$follow) {
            return $this->createResponse('No estás siguiendo a este usuario', false, null, 404);
        }

        $follow->delete();
        return $this->createResponse('Has dejado de seguir a este usuario', false, null, 200);
    }

    /**
     * Acepta una solicitud de seguimiento pendiente
     */
    public function acceptFollow(Request $request, $id): JsonResponse
    {
        // Buscamos la solicitud donde el usuario actual es el que recibe la solicitud
        $follow = Follow::where('follower_id', '=', $id)
            ->where('following_id', '=', auth()->id())
            ->where('status', '=', 'pending')
            ->first();

        if (!$follow) {
            return $this->createResponse('No hay solicitud de seguimiento pendiente de este usuario', false, null, 404);
        }

        // Actualizamos el estado a aceptado
        $follow->status = 'accepted';
        $follow->save();

        // Puedes disparar un evento aquí si necesitas notificar al seguidor
        // event(new FollowRequestAccepted($follow->follower, auth()->user()));

        return $this->createResponse('Has aceptado la solicitud de seguimiento', true, 'accepted', 200);
    }

    /**
     * Rechaza una solicitud de seguimiento pendiente
     */
    public function rejectFollow(Request $request, $id): JsonResponse
    {
        // Buscamos la solicitud donde el usuario actual es el que recibe la solicitud
        $follow = Follow::where('follower_id', '=', $id)
            ->where('following_id', '=', auth()->id())
            ->where('status', '=', 'pending')
            ->first();

        if (!$follow) {
            return $this->createResponse('No hay solicitud de seguimiento pendiente de este usuario', false, null, 404);
        }

        // Eliminamos la solicitud en lugar de marcarla como rechazada
        $follow->delete();

        return $this->createResponse('Has rechazado la solicitud de seguimiento', false, null, 200);
    }

    /**
     * Crea una respuesta JSON estandarizada
     */
    private function createResponse(string $message, bool $isFollowing, ?string $status, int $code): JsonResponse
    {
        return response()->json([
            'success' => $code >= 200 && $code < 300,
            'message' => $message,
            'is_following' => $isFollowing,
            'status' => $status
        ], $code);
    }
}
