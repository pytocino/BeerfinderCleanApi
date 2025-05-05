<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeerReviewResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'beer' => new BeerResource($this->whenLoaded('beer')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'post_id' => $this->post_id,
            'rating' => $this->rating,
            'review_text' => $this->review_text,
            'serving_type' => $this->serving_type,
            'serving_type_label' => $this->getFormattedServingType(),
            'purchase_price' => $this->purchase_price,
            'purchase_currency' => $this->purchase_currency,
            'formatted_price' => $this->getFormattedPrice(),
            'is_public' => $this->is_public,
            'has_review_text' => $this->hasReviewText(),
            'review_excerpt' => $this->getReviewExcerpt(),
            'rating_stars' => $this->getRatingStars(),
            'is_high_rating' => $this->isHighRating(),
            'is_low_rating' => $this->isLowRating(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
