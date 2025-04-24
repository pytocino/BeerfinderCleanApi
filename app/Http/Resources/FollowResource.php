<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'follower' => [
                'id' => $this->follower?->id,
                'name' => $this->follower?->name,
                'username' => $this->follower?->username,
                'profile_picture' => $this->follower?->profile_picture,
            ],
            'following' => [
                'id' => $this->following?->id,
                'name' => $this->following?->name,
                'username' => $this->following?->username,
                'profile_picture' => $this->following?->profile_picture,
            ],
            'accepted' => $this->accepted,
            'followed_at' => $this->followed_at,
            'unfollowed_at' => $this->unfollowed_at,
        ];
    }
}
