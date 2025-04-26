<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\BeerResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'beer_id' => $this->beer_id,
            'location_id' => $this->location_id,
            'review' => $this->review,
            'rating' => $this->rating,
            'photo_url' => $this->photo_url,
            'additional_photos' => $this->additional_photos,
            'serving_type' => $this->serving_type,
            'purchase_price' => $this->purchase_price,
            'purchase_currency' => $this->purchase_currency,
            'user_tags' => $this->user_tags, // Ya está casteado a array por el modelo
            'likes_count' => $this->likes->count(),
            'comments_count' => $this->whenLoaded('comments') ? $this->comments->count() : 0,
            'edited' => $this->edited,
            'edited_at' => $this->edited_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Incluir información mínima del usuario
            'user' => [
                'id' => $this->whenLoaded('user') ? $this->user->id : null,
                'name' => $this->whenLoaded('user') ? $this->user->name : null,
                'username' => $this->whenLoaded('user') ? $this->user->username : null,
                'profile_picture' => $this->whenLoaded('user') ? $this->user->profile_picture : null,
            ],
            // Incluir información mínima de la cerveza
            'beer' => $this->whenLoaded('beer', function () {
                return [
                    'id' => $this->beer->id,
                    'name' => $this->beer->name,
                    'image_url' => $this->beer->image_url,
                ];
            }),
            // Incluir información mínima de la ubicación
            'location' => $this->whenLoaded('location', function () {
                return [
                    'id' => $this->location->id,
                    'name' => $this->location->name,
                    'address' => $this->location->address,
                    'latitude' => $this->location->latitude,
                    'longitude' => $this->location->longitude,
                ];
            }),
            // En lugar de cargar todos los likes, solo indica si el usuario actual le dio like
            'user_liked' => auth()->check() ? $this->likes->contains('user_id', auth()->id()) : false,
        ];
    }
}
