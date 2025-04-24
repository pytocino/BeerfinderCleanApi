<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender' => [
                'id' => $this->sender?->id,
                'name' => $this->sender?->name,
                'username' => $this->sender?->username,
            ],
            'receiver' => [
                'id' => $this->receiver?->id,
                'name' => $this->receiver?->name,
                'username' => $this->receiver?->username,
            ],
            'content' => $this->content,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at,
        ];
    }
}
