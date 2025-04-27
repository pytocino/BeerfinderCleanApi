<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'user_id' => $this->user_id,
            'beer_id' => $this->beer_id,
            'created_at' => $this->created_at,
            'beer' => $this->whenLoaded('beer', function () {
                return [
                    'id' => $this->beer->id,
                    'name' => $this->beer->name,
                    'brewery' => $this->beer->brewery,
                    'image_url' => $this->beer->image_url,
                ];
            }),
        ];
    }
}
