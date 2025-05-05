<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'post_id' => $this->post_id,
            'content' => $this->content,
            'parent_id' => $this->parent_id,
            'edited' => $this->edited,
            'pinned' => $this->pinned,
            'edited_at' => $this->edited_at,
            'likes_count' => $this->likes_count,
            'is_reply' => $this->isReply(),
            'has_replies' => $this->hasReplies(),
            'replies_count' => $this->getRepliesCount(),
            'excerpt' => $this->getExcerpt(),
            'parent' => new CommentResource($this->whenLoaded('parent')),
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
