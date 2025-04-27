<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'post_id'   => $this->post_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user'      => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'username' => $this->user->username,
                    'profile_picture' => $this->user->profile_picture,
                ];
            }),
        ];
    }
}
