<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasUser;

class PostResource extends JsonResource
{
    use HasUser;

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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'profile_picture' => $this->user->profile_picture,
                'is_me' => $this->getUserId() ? $this->user->id === $this->getUserId() : false,
                'is_followed' => $this->getUserId() ? $this->user->followers()->where('users.id', $this->getUserId())->wherePivot('status', 'accepted')->exists() : false,
            ],
            'beer_review' => $this->whenLoaded('beerReview', function () {
                $beerReview = $this->beerReview;
                if (!$beerReview) return null;
                return [
                    'id' => $beerReview->id,
                    'beer' => $beerReview->relationLoaded('beer') && $beerReview->beer ? [
                        'id' => $beerReview->beer->id,
                        'name' => $beerReview->beer->name,
                    ] : null,
                    'location' => $beerReview->relationLoaded('location') && $beerReview->location ? [
                        'id' => $beerReview->location->id,
                        'name' => $beerReview->location->name,
                    ] : null,
                ];
            }),
            'content' => $this->content,
            'photo_url' => $this->photo_url,
            'additional_photos' => $this->additional_photos,
            'all_photos' => $this->getAllPhotos(),
            'user_tags' => $this->user_tags,
            'comments_count' => $this->getCommentsCount(),
            'likes_count' => $this->getLikesCount(),
            'is_liked' => $this->when(
                $this->getUserId(),
                function () {
                    return $this->isLikedBy($this->getUserId());
                }
            ),
            'edited' => $this->edited,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
