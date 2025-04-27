<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeerStyleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'origin_country' => $this->origin_country,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'beers_count' => isset($this->beers_count) ? (int)$this->beers_count : null,
        ];
    }
}
