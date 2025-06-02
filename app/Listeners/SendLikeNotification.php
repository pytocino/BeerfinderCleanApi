<?php

namespace App\Listeners;

use App\Events\PostLiked;
use App\Notifications\UserLikedPost;
use App\Services\NotificationDeduplicationService;

class SendLikeNotification
{
    public function handle(PostLiked $event)
    {
        if ($event->user->id !== $event->post->user_id) {
            // Usar servicio de deduplicación
            if (!NotificationDeduplicationService::isDuplicate(
                'like',
                $event->post->user_id,
                ['liker_id' => $event->user->id, 'post_id' => $event->post->id]
            )) {
                $event->post->user->notify(
                    new UserLikedPost($event->user, $event->post)
                );
            }
        }
    }
}
