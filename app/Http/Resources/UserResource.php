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

        // Obtener información de seguimiento si hay usuario autenticado
        $followStatus = null;
        $isFollowing = false;

        if (Auth::check()) {
            $follow = Follow::where('follower_id', '=', Auth::id())
                ->where('following_id', '=', $this->id)
                ->first();

            if ($follow) {
                $followStatus = $follow->status;
                $isFollowing = $follow->status === 'accepted';
            }
        }

        // Contar solo seguidores/seguidos aceptados
        $followersCount = isset($this->followers_count)
            ? (int) $this->followers_count  // Si viene pre-contado por withCount
            : ($this->whenLoaded('followers')
                ? $this->followers->where('follows.status', '=', 'accepted')->count()
                : null);

        $followingCount = isset($this->following_count)
            ? (int) $this->following_count  // Si viene pre-contado por withCount
            : ($this->whenLoaded('following')
                ? $this->following->where('follows.status', '=', 'accepted')->count()
                : null);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'profile_picture' => $this->profile_picture,
            'bio' => $profile?->bio ?? null,
            'location' => $profile?->location ?? null,
            'birthdate' => $profile?->birthdate ?? null,
            'private_profile' => $profile?->private_profile ?? null,

            'followers_count' => $followersCount,
            'following_count' => $followingCount,
            'posts_count' => isset($this->posts_count)
                ? (int) $this->posts_count
                : ($this->whenLoaded('posts') ? $this->posts->count() : null),

            // Información de seguimiento
            'is_following' => $this->when(Auth::check(), function () use ($isFollowing) {
                return $isFollowing;
            }),
            'follow_status' => $this->when(Auth::check(), function () use ($followStatus) {
                return $followStatus;
            }),
        ];
    }
}
