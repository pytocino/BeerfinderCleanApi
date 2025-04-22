<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Datos básicos del like
        $data = [
            'id' => $this->id,
            'check_in_id' => $this->check_in_id,
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u\Z'),
        ];

        // Información del usuario
        if ($this->relationLoaded('user')) {
            $userData = [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'profile_picture' => $this->user->profile_picture,
            ];

            // Si el recurso tiene un indicador is_following, incluirlo
            if (isset($this->user->is_following)) {
                $userData['is_following'] = (bool)$this->user->is_following;
            }

            // Si el recurso tiene un indicador is_followed_by, incluirlo
            if (isset($this->user->is_followed_by)) {
                $userData['is_followed_by'] = (bool)$this->user->is_followed_by;
            }

            $data['user'] = $userData;
        } else {
            // Si la relación no está cargada, al menos incluir el ID de usuario
            $data['user_id'] = $this->user_id;
        }

        // Incluir información del check-in si está cargada la relación
        if ($this->relationLoaded('checkIn')) {
            $checkInData = [
                'id' => $this->checkIn->id,
            ];

            // Incluir información de la cerveza si está disponible
            if ($this->checkIn->relationLoaded('beer')) {
                $checkInData['beer'] = [
                    'id' => $this->checkIn->beer->id,
                    'name' => $this->checkIn->beer->name,
                    'image_url' => $this->checkIn->beer->image_url,
                ];

                // Incluir información de cervecería si está disponible
                if ($this->checkIn->beer->relationLoaded('brewery')) {
                    $checkInData['beer']['brewery'] = [
                        'id' => $this->checkIn->beer->brewery->id,
                        'name' => $this->checkIn->beer->brewery->name,
                    ];
                }
            }

            $data['check_in'] = $checkInData;
        }

        return $data;
    }

    /**
     * Método para crear una colección simplificada de likes
     * Útil para listas donde solo se necesita información básica
     */
    public static function collectionSimple($resource)
    {
        return $resource->map(function ($like) {
            return [
                'id' => $like->id,
                'user' => [
                    'id' => $like->user->id,
                    'name' => $like->user->name,
                    'profile_picture' => $like->user->profile_picture,
                ],
                'created_at' => $like->created_at->format('Y-m-d\TH:i:s.u\Z'),
            ];
        });
    }

    /**
     * Método para crear una colección extendida con información del usuario actual
     * Útil para contextos sociales donde se necesita saber las relaciones entre usuarios
     */
    public static function collectionWithRelationships($resource, $currentUserId)
    {
        return $resource->map(function ($like) use ($currentUserId) {
            // Datos del like y usuario básicos
            $data = [
                'id' => $like->id,
                'user' => [
                    'id' => $like->user->id,
                    'name' => $like->user->name,
                    'profile_picture' => $like->user->profile_picture,
                ],
                'created_at' => $like->created_at->format('Y-m-d\TH:i:s.u\Z'),
            ];

            // Determinar si el usuario actual sigue a este usuario
            if ($currentUserId) {
                $isFollowing = \App\Models\Follow::where('follower_id', $currentUserId)
                    ->where('followed_id', $like->user->id)
                    ->exists();

                $data['user']['is_following'] = $isFollowing;

                // También podemos determinar la relación inversa si es útil
                $isFollowedBy = \App\Models\Follow::where('follower_id', $like->user->id)
                    ->where('followed_id', $currentUserId)
                    ->exists();

                $data['user']['is_followed_by'] = $isFollowedBy;
            }

            return $data;
        });
    }
}
