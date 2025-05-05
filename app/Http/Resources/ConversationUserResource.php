<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationUserResource extends JsonResource
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
            'conversation_id' => $this->conversation_id,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'role' => $this->role,
            'is_member' => $this->isMember(),
            'is_admin' => $this->isAdmin(),
            'is_owner' => $this->isOwner(),
            'can_add_members' => $this->canAddMembers(),
            'is_muted' => $this->is_muted,
            'joined_at' => $this->joined_at,
            'left_at' => $this->left_at,
            'last_read_at' => $this->last_read_at,
            'unread_count' => $this->getUnreadCount(),
            'has_left' => $this->hasLeft(),
            'conversation' => new ConversationResource($this->whenLoaded('conversation')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
