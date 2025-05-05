<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowResource extends JsonResource
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
            'follower_id' => $this->follower_id,
            'follower' => new UserResource($this->whenLoaded('follower')),
            'following_id' => $this->following_id,
            'following' => new UserResource($this->whenLoaded('following')),
            'status' => $this->status,
            'is_pending' => $this->isPending(),
            'is_accepted' => $this->isAccepted(),
            'is_rejected' => $this->isRejected(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
