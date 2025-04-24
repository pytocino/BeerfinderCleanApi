<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'post_id'    => $this->post_id,
            'user_id'    => $this->user_id,
            'parent_id'  => $this->parent_id,
            'content'    => $this->content,
            'edited'     => (bool) $this->edited,
            'edited_at'  => $this->edited_at,
            'pinned'     => (bool) ($this->pinned ?? false),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user'       => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'username' => $this->user->username,
                    'profile_picture' => $this->user->profile_picture,
                ];
            }),
            'replies'    => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
