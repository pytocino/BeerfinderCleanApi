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
            'user' => new UserResource($this->whenLoaded('user')),
            'content' => $this->content,
            'photo_url' => $this->photo_url,
            'additional_photos' => $this->additional_photos,
            'all_photos' => $this->getAllPhotos(),
            'has_photo' => $this->hasPhoto(),
            'has_additional_photos' => $this->hasAdditionalPhotos(),
            'total_photos_count' => $this->getTotalPhotosCount(),
            'user_tags' => $this->user_tags,
            'tagged_users' => UserResource::collection($this->whenLoaded('taggedUsers')),
            'has_user_tags' => $this->hasUserTags(),
            'comments_count' => $this->getCommentsCount(),
            'likes_count' => $this->getLikesCount(),
            'is_liked' => $this->when(
                $this->getUserId(),
                function () {
                    return $this->isLikedBy($this->getUserId());
                }
            ),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'beer_review' => new BeerReviewResource($this->whenLoaded('beerReview')),
            'edited' => $this->edited,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
