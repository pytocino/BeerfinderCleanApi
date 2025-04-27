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
            'post_id' => $this->post_id,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'username' => $this->user->username,
                ];
            }),
            'beer' => $this->whenLoaded('beer', function () {
                return [
                    'id' => $this->beer->id,
                    'name' => $this->beer->name,
                ];
            }),
            'location' => $this->whenLoaded('location', function () {
                return $this->location ? [
                    'id' => $this->location->id,
                    'name' => $this->location->name,
                ] : null;
            }),
            'post' => $this->whenLoaded('post', function () {
                return $this->post ? [
                    'id' => $this->post->id,
                    'review' => $this->post->review,
                ] : null;
            }),
        ];
    }
}
