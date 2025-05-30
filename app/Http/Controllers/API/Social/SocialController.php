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
        // Cargar usuario con su perfil
        $followedUser = User::with('profile')->find($id);

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
                    // TODO: Descomentar cuando se implemente el evento UserFollowed
                    //event(new UserFollowed($this->authenticatedUser(), $followedUser));
                    return response()->json([
                        'message' => 'Solicitud de seguimiento reenviada',
                        'is_following' => false,
                        'status' => 'pending'
                    ]);
            }
        }
        // Determinar el estado basado en si el perfil es privado
        $isPrivateProfile = $followedUser->profile && $followedUser->profile->private_profile;
        $status = $isPrivateProfile ? 'pending' : 'accepted';

        Follow::create([
            'follower_id' => $this->getUserId(),
            'following_id' => $id,
            'status' => $status,
        ]);

        // TODO: Descomentar cuando se implemente el evento UserFollowed
        //event(new UserFollowed($this->authenticatedUser(), $followedUser));

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

        // Eliminamos la solicitud en lugar de marcarla como rechazada
        $follow->delete();

        return response()->json([
            'message' => 'Has rechazado la solicitud de seguimiento',
            'is_following' => false,
            'status' => null
        ]);
    }
}
