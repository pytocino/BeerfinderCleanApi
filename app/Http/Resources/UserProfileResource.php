<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'bio' => $this->bio,
            'location' => $this->location,
            'birthdate' => $this->birthdate,
            'website' => $this->website,
            'phone' => $this->phone,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'private_profile' => (bool) $this->private_profile,
            'allow_mentions' => (bool) $this->allow_mentions,
            'email_notifications' => (bool) $this->email_notifications,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
