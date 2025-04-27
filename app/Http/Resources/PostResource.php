<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'beer_id' => $this->beer_id,
            'location_id' => $this->location_id,
            'review' => $this->review,
            'rating' => $this->rating,
            'photo_url' => $this->photo_url,
            'additional_photos' => $this->additional_photos,
            'serving_type' => $this->serving_type,
            'purchase_price' => $this->purchase_price,
            'purchase_currency' => $this->purchase_currency,
            'user_tags' => $this->user_tags,
            'likes_count' => $this->whenLoaded('likes', fn() => $this->likes->count()),
            'comments_count' => $this->whenLoaded('comments', fn() => $this->comments->count()),
            'edited' => (bool) $this->edited,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'username' => $this->user->username,
                    'profile_picture' => $this->user->profile_picture,
                ];
            }),
            'beer' => $this->whenLoaded('beer', function () {
                return [
                    'id' => $this->beer->id,
                    'name' => $this->beer->name,
                    'image_url' => $this->beer->image_url,
                ];
            }),
            'location' => $this->whenLoaded('location', function () {
                return $this->location ? [
                    'id' => $this->location->id,
                    'name' => $this->location->name,
                    'address' => $this->location->address,
                    'latitude' => $this->location->latitude,
                    'longitude' => $this->location->longitude,
                ] : null;
            }),
            'user_liked' => auth()->check() ? $this->likes->contains('user_id', auth()->id()) : false,
        ];
    }
}
