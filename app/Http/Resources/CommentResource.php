<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'parent_id' => $this->parent_id,
            'edited' => $this->edited,
            'edited_at' => $this->edited_at,
            'pinned' => $this->pinned,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' =>  $this->user->id,
                'name' => $this->user->name,
                'profile_picture' => $this->user->profile_picture,
            ],
            'parent' => $this->when($this->parent_id !== null, function () {
                return $this->whenLoaded('parent', function () {
                    return [
                        'id' => $this->parent->id,
                        'content' => $this->parent->content,
                        'user' => [
                            'id' =>  $this->parent->user->id,
                            'name' => $this->parent->user->name,
                            'profile_picture' => $this->parent->user->profile_picture,
                        ],
                    ];
                });
            }),
        ];
    }
}
