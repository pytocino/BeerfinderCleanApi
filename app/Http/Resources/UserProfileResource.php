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
        $isMe = $this->belongsToAuthenticatedUser();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'bio' => $this->bio,
            'location' => $this->location,
            'website' => $this->website,
            'has_social_links' => (bool) $this->hasSocialLinks(),
            'social_links' => $this->getSocialLinks(),
            'allow_mentions' => (bool) $this->allow_mentions,
            'is_me' => $isMe,
            // Solo para el usuario autenticado:
            'birthdate' => $this->when($isMe, $this->birthdate),
            'is_birthday' => $this->when($isMe, $this->isBirthday()),
            'age' => $this->when($isMe, $this->getAge()),
            'phone' => $this->when($isMe, $this->phone),
            'formatted_phone' => $this->when($isMe, $this->getFormattedPhone()),
            'timezone' => $this->when($isMe, $this->timezone),
            'instagram' => $this->when($isMe, $this->instagram),
            'twitter' => $this->when($isMe, $this->twitter),
            'facebook' => $this->when($isMe, $this->facebook),
            'email_notifications' => $this->when($isMe, $this->email_notifications),
            'has_email_notifications_enabled' => $this->when($isMe, $this->hasEmailNotificationsEnabled()),
            'created_at' => $this->when($isMe, $this->created_at),
            'updated_at' => $this->when($isMe, $this->updated_at),
        ];
    }
}
