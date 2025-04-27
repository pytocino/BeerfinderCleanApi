<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brewery' => $this->brewery,
            'style' => $this->whenLoaded('style', function () {
                return [
                    'id' => $this->style->id,
                    'name' => $this->style->name,
                ];
            }),
            'abv' => $this->abv,
            'ibu' => $this->ibu,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
