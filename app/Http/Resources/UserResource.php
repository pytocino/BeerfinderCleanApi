<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'profile_picture' => $this->profile_picture,
            'bio' => $this->bio,
            'location' => $this->location,
            'birthdate' => $this->birthdate,
            'website' => $this->website,
            'phone' => $this->phone,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'private_profile' => $this->private_profile,
            'allow_mentions' => $this->allow_mentions,
            'email_notifications' => $this->email_notifications,
            'last_active_at' => $this->last_active_at,
            'email_verified_at' => $this->when(Auth::id() === $this->id, $this->email_verified_at),
            'email' => $this->when(Auth::id() === $this->id, $this->email),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Contadores calculados en la consulta
            'check_ins_count' => isset($this->check_ins_count) ? (int)$this->check_ins_count : null,
            'followers_count' => isset($this->followers_count) ? (int)$this->followers_count : null,
            'following_count' => isset($this->following_count) ? (int)$this->following_count : null,
        ];
    }
}
