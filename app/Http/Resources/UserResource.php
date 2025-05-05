<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasUser;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->when($this->belongsToAuthenticatedUser(), $this->email),
            'profile_picture' => $this->profile_picture,
            'is_admin' => $this->is_admin,
            'private_profile' => $this->private_profile,
            'status' => $this->status,
            'last_active_at' => $this->last_active_at,
            'profile' => new UserProfileResource($this->whenLoaded('profile')),
            'followers_count' => $this->whenCounted('followers'),
            'following_count' => $this->whenCounted('following'),
            'posts_count' => $this->whenCounted('posts'),
            'beer_reviews_count' => $this->whenCounted('beerReviews'),
            'comments_count' => $this->whenCounted('comments'),
            'is_me' => $this->belongsToAuthenticatedUser(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
