<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreweryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'city' => $this->city,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'description' => $this->description,
            'logo_url' => $this->logo_url,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'cover_photo' => $this->cover_photo,
            'founded' => $this->founded,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'beers_count' => isset($this->beers_count) ? (int)$this->beers_count : null,
        ];
    }
}
