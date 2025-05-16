<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeerStyleResource extends JsonResource
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
            'short_description' => $this->getShortDescription(),
            'origin_country' => $this->origin_country,
            'beers_count' => $this->whenCounted('beers'),
            'top_rated_beers' => BeerResource::collection($this->whenLoaded('beers', function () {
                return $this->getTopRatedBeers();
            })),
            'related_styles' => BeerStyleResource::collection($this->whenLoaded('relatedStyles')),
            'breweries' => BreweryResource::collection($this->whenLoaded('breweries')),
            'typical_characteristics' => $this->getTypicalCharacteristics(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
