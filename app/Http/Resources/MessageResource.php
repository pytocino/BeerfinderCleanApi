<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'content' => $this->content,
            'attachments' => $this->attachments,
            'attachments_by_type' => $this->getAttachmentsByType(),
            'first_image_preview' => $this->getFirstImagePreview(),
            'reply_to' => $this->reply_to,
            'reply_to_message' => new MessageResource($this->whenLoaded('replyToMessage')),
            'replies' => MessageResource::collection($this->whenLoaded('replies')),
            'is_edited' => $this->is_edited,
            'is_read' => $this->isRead(),
            'read_at' => $this->read_at,
            'has_attachments' => $this->hasAttachments(),
            'is_reply' => $this->isReply(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
