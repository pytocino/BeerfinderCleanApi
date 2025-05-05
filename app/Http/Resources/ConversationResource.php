<?php

namespace App\Http\Resources;

use App\Traits\HasUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
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
        $userId = $this->getUserId();

        return [
            'id' => $this->id,
            'title' => $this->getTitleFor($userId),
            'type' => $this->type,
            'description' => $this->description,
            'image_url' => $this->getImageUrl($userId),
            'is_public' => $this->is_public,
            'group_settings' => $this->group_settings,
            'created_by' => $this->created_by,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'participants' => UserResource::collection($this->whenLoaded('participants')),
            'messages_count' => $this->whenCounted('messages'),
            'last_message_at' => $this->last_message_at,
            'last_message' => new MessageResource($this->whenLoaded('lastMessage')),
            'is_direct' => $this->isDirect(),
            'is_group' => $this->isGroup(),
            'is_admin' => $userId ? $this->isAdmin($userId) : false,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
