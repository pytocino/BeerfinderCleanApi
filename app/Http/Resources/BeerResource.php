<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasUser;

class BeerResource extends JsonResource
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'brewery' => new BreweryResource($this->whenLoaded('brewery')),
            'style' => new BeerStyleResource($this->whenLoaded('style')),
            'abv' => $this->abv,
            'ibu' => $this->ibu,
            'image_url' => $this->image_url,
            'ratings_count' => $this->ratings_count ?? 0,
            'bitterness_level' => $this->getBitternessLevel(),
            'alcohol_level' => $this->getAlcoholLevel(),
            'is_favorited' => $this->when(
                $this->authenticatedUser()->id,
                function () {
                    return $this->isFavoritedBy($this->authenticatedUser()->id);
                }
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Relaciones opcionales
            'reviews_count' => $this->whenCounted('reviews'),
            'favorited_by_count' => $this->whenCounted('favoritedBy'),
            'locations' => LocationResource::collection($this->whenLoaded('locations')),
            // Información para búsquedas por proximidad
            'distance_km' => $this->when(isset($this->distance_km), $this->distance_km),
            'location_info' => $this->when(isset($this->location_info), $this->location_info),
        ];
    }
}
