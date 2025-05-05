<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasUser;

class UserProfileResource extends JsonResource
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
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'bio' => $this->bio,
            'location' => $this->location,
            'birthdate' => $this->birthdate,
            'is_birthday' => $this->isBirthday(),
            'age' => $this->getAge(),
            'website' => $this->website,
            'phone' => $this->phone,
            'formatted_phone' => $this->getFormattedPhone(),
            'timezone' => $this->timezone,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'has_social_links' => $this->hasSocialLinks(),
            'social_links' => $this->getSocialLinks(),
            'allow_mentions' => $this->allow_mentions,
            'allows_mentions' => $this->allowsMentions(),
            'email_notifications' => $this->email_notifications,
            'has_email_notifications_enabled' => $this->hasEmailNotificationsEnabled(),
            'is_me' => $this->belongsToAuthenticatedUser(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
