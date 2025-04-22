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
            'brewery' => [
                'id' => $this->brewery->id,
                'name' => $this->brewery->name,
                'country' => $this->brewery->country,
            ],
            'style' => [
                'id' => $this->style->id,
                'name' => $this->style->name,
            ],
            'abv' => $this->abv,
            'ibu' => $this->ibu,
            'color' => $this->color,
            'label_image_url' => $this->label_image_url,
            'package_type' => $this->package_type,
            'availability' => $this->availability,
            'origin_country' => $this->origin_country,
            'collaboration' => $this->collaboration,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'first_brewed' => $this->first_brewed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
