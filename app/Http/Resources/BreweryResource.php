<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreweryResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'country' => $this->country,
            'city' => $this->city,
            'full_location' => $this->getFullLocation(),
            'image_url' => $this->getImageUrl(),
            'website' => $this->website,
            'has_location' => $this->hasLocation(),
            'has_contact_info' => $this->hasContactInfo(),
            'beers_count' => $this->whenCounted('beers'),
            'average_rating' => $this->calculateAverageRating(),
            'top_rated_beers' => BeerResource::collection($this->whenLoaded('beers', function () {
                return $this->getTopRatedBeers();
            })),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
