<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckInResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'beer_id' => $this->beer_id,
            'location_id' => $this->location_id,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Incluyo solo las relaciones que están en $with
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'beer' => [
                'id' => $this->beer->id,
                'name' => $this->beer->name,
            ],
            'location' => [
                'id' => $this->location->id,
                'name' => $this->location->name,
            ],
        ];
    }
}
