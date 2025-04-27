<?php

namespace App\Http\Resources;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $profile = $this->whenLoaded('profile');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'profile_picture' => $this->profile_picture,
            'email_verified_at' => $this->when(Auth::id() === $this->id, $this->email_verified_at),
            'last_active_at' => $this->last_active_at,
            'is_admin' => $this->is_admin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'followers_count' => isset($this->followers_count)
                ? (int) $this->followers_count
                : ($this->whenLoaded('followers') ? $this->followers->count() : null),
            'following_count' => isset($this->following_count)
                ? (int) $this->following_count
                : ($this->whenLoaded('following') ? $this->following->count() : null),
            'posts_count' => isset($this->posts_count)
                ? (int) $this->posts_count
                : ($this->whenLoaded('posts') ? $this->posts->count() : null),
            'is_following' => $this->when(Auth::check(), function () {
                return Follow::where('follower_id', Auth::id())
                    ->where('following_id', $this->id)
                    ->where('status', 'accepted')
                    ->exists();
            }),
        ];
    }
}
