<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'related_id' => $this->related_id,
            'is_read' => $this->is_read,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'time_ago' => $this->created_at->diffForHumans(),

            // Usuario que recibe la notificación
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),

            // Usuario que generó la acción (puede ser null para notificaciones del sistema)
            'from_user' => $this->when($this->from_user_id, function () {
                return $this->whenLoaded('fromUser', function () {
                    return [
                        'id' => $this->fromUser->id,
                        'name' => $this->fromUser->name,
                        'profile_picture' => $this->fromUser->profile_picture,
                    ];
                });
            }),
        ];
    }
}
