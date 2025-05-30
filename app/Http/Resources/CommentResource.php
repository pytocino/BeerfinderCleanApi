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
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'edited' => (bool) $this->edited,
            'edited_at' => $this->edited_at,
            'excerpt' => $this->getExcerpt(),
            'has_replies' => $this->hasReplies(),
            'is_liked_by_user' => $this->when($request->user() !== null, function () use ($request) {
                return $this->isLikedByUser($request->user()->id);
            }),
            'is_pinned' => (bool) $this->pinned,
            'is_reply' => $this->isReply(),
            'likes' => $this->whenLoaded('likes', function () {
                return $this->likes->pluck('user_id');
            }),
            'likes_count' => $this->likes_count,
            'parent_id' => $this->parent_id,
            'post_id' => $this->post_id,
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
            'replies_count' => $this->getRepliesCount(),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
