<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeerLocationResource extends JsonResource
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
            'beer_id' => $this->beer_id,
            'location_id' => $this->location_id,
            'price' => $this->price,
            'formatted_price' => $this->getFormattedPrice(),
            'is_featured' => $this->is_featured,
            'is_budget_friendly' => $this->isBudgetFriendly(),
            'is_cheapest_location' => $this->isCheapestLocation(),
            'beer' => new BeerResource($this->whenLoaded('beer')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
