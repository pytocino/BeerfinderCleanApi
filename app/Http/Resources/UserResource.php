<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth; // Necesario para comprobar el usuario autenticado

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Devuelve los datos del usuario con los campos especificados.
     * Asegúrate de que los contadores ('check_ins_count', 'followers_count', 'following_count')
     * se hayan cargado en el modelo User (usando withCount o loadCount) antes de usar este Resource.
     * El email solo se incluirá si es el usuario autenticado.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'              => $this->id, // Es buena práctica incluir el ID
            'name'            => $this->name,
            'profile_picture' => $this->profile_picture,
            'bio'             => $this->whenNotNull($this->bio), // Incluir solo si no es null
            'location'        => $this->whenNotNull($this->location), // Incluir solo si no es null

            // Incluir contadores solo si están cargados en el modelo
            'check_ins_count' => $this->when(isset($this->check_ins_count), fn() => (int) $this->check_ins_count),
            'followers_count' => $this->when(isset($this->followers_count), fn() => (int) $this->followers_count),
            'following_count' => $this->when(isset($this->following_count), fn() => (int) $this->following_count),
        ];

        // Incluir email solo si es el usuario autenticado el que solicita sus propios datos
        // o si la ruta específica lo permite (ej. 'auth.me').
        // Comprobamos que el atributo 'email' existe en el modelo antes de intentar accederlo.
        if (isset($this->email) && Auth::check() && Auth::id() === $this->id) {
            $data['email'] = $this->email;
        }


        return $data;
    }
}
