<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // Ordenar los horarios de apertura por día de la semana
        $orderedDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $openingHours = $this->opening_hours ?? [];
        $sortedOpeningHours = [];
        foreach ($orderedDays as $day) {
            if (isset($openingHours[$day])) {
                $sortedOpeningHours[$day] = $openingHours[$day];
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'status' => $this->status,
            'opening_hours' => $sortedOpeningHours,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'image_url' => $this->image_url,
            'cover_photo_url' => $this->cover_photo,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'verified' => $this->verified,
            'is_active' => $this->isActive(),
            'is_open_now' => $this->isOpenNow(),
            'today_hours' => $this->getTodayHours(),
            'full_address' => $this->getFullAddress(),
            'featured_beers' => BeerResource::collection($this->whenLoaded('beers', function () {
                return $this->getFeaturedBeers();
            })),
            'beers' => BeerResource::collection($this->whenLoaded('beers')),
            'beers_count' => $this->whenCounted('beers'),
            // Información de distancia cuando está disponible (para búsquedas por proximidad)
            'distance_km' => $this->when(isset($this->distance_km), $this->distance_km),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
