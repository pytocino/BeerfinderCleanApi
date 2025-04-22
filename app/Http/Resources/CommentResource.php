<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Datos básicos siempre presentes
        $data = [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u\Z'),
            'updated_at' => $this->when(
                $this->updated_at && $this->updated_at->ne($this->created_at),
                fn() => $this->updated_at->format('Y-m-d\TH:i:s.u\Z')
            ),
        ];

        // Añadir información del usuario (siempre cargar esta relación)
        if ($this->relationLoaded('user')) {
            $data['user'] = [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'profile_picture' => $this->user->profile_picture,
                'username' => $this->when(isset($this->user->username), $this->user->username),
            ];
        } else {
            // Fallback por si la relación no se cargó
            $data['user_id'] = $this->user_id;
        }

        // Añadir información sobre la relación con el check-in
        if ($this->check_in_id) {
            $data['check_in_id'] = $this->check_in_id;
        }

        // Si está cargada la relación de check-in, añadir datos básicos
        if ($this->relationLoaded('checkIn')) {
            $data['check_in'] = [
                'id' => $this->checkIn->id,
                'beer' => $this->when($this->checkIn->relationLoaded('beer'), function () {
                    return [
                        'id' => $this->checkIn->beer->id,
                        'name' => $this->checkIn->beer->name,
                    ];
                }),
            ];
        }

        // Indicar si el comentario ha sido editado
        if (isset($this->is_edited) || ($this->updated_at && $this->updated_at->ne($this->created_at))) {
            $data['is_edited'] = isset($this->is_edited) ? (bool)$this->is_edited : true;
        }

        // Información de permisos para el usuario actual
        $currentUser = $request->user();
        if ($currentUser) {
            $data['permissions'] = [
                'can_edit' => $currentUser->id === $this->user_id || $currentUser->is_admin,
                'can_delete' => $currentUser->id === $this->user_id || $currentUser->is_admin ||
                    ($this->relationLoaded('checkIn') && $currentUser->id === $this->checkIn->user_id),
                'can_report' => $currentUser->id !== $this->user_id,
            ];
        }

        // Si hay campo de reportes, incluirlo
        if (isset($this->reports_count)) {
            $data['reports_count'] = (int)$this->reports_count;
        }

        // Si hay reacciones o valoraciones
        if (isset($this->likes_count)) {
            $data['likes_count'] = (int)$this->likes_count;
        }

        // Añadir campo que indica si el comentario es de un moderador/admin
        if ($this->relationLoaded('user') && isset($this->user->is_admin) && $this->user->is_admin) {
            $data['is_from_admin'] = true;
        }

        return $data;
    }

    /**
     * Crea una colección personalizada para mostrar comentarios sin información extra
     */
    public static function collectionSimple($resource)
    {
        return $resource->map(function ($item) {
            // Mostrar solo campos esenciales para listas
            return [
                'id' => $item->id,
                'user' => [
                    'id' => $item->user->id,
                    'name' => $item->user->name,
                    'profile_picture' => $item->user->profile_picture,
                ],
                'content' => $item->content,
                'created_at' => $item->created_at->format('Y-m-d\TH:i:s.u\Z'),
            ];
        });
    }
}
