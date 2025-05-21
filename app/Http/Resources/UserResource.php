<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasUser;

class UserResource extends JsonResource
{
    use HasUser;

    /**
     * Transformar el recurso en un array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $authUser = request()?->user();
        $isMe = $authUser && $authUser->id === $this->id;
        $isPrivate = (bool) $this->private_profile;
        $isFollowed = false;
        if ($authUser && !$isMe && $isPrivate) {
            $isFollowed = $this->followers()
                ->where('users.id', $authUser->id)
                ->wherePivot('status', 'accepted')
                ->exists();
        }
        // Si el usuario es privado y no es el autenticado ni seguido, solo mostrar datos mínimos
        if ($isPrivate && !$isMe && !$isFollowed) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'username' => $this->username,
                'profile_picture' => $this->profile_picture,
                'private_profile' => true,
            ];
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'profile_picture' => $this->profile_picture,
            'private_profile' => $isPrivate,
            'status' => $this->status,
            'last_active_at' => $this->last_active_at,
            'profile' => $this->whenLoaded('profile', function () use ($isMe) {
                return new UserProfileResource($this->profile);
            }),
            'is_me' => $isMe,
            // Solo para el usuario autenticado:
            'email' => $this->when($isMe, $this->email),
            'created_at' => $this->when($isMe, $this->created_at),
            'updated_at' => $this->when($isMe, $this->updated_at),
        ];
    }
}
