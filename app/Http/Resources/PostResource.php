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
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments_count,
            'edited' => $this->edited,
            'edited_at' => $this->edited_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'comments' => $this->comments,
            'likes' => $this->likes,
        ];
    }
}
