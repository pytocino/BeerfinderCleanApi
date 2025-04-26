<?php

namespace App\Listeners;

use App\Events\PostLiked;
use App\Notifications\UserLikedPost;

class SendLikeNotification
{
    public function handle(PostLiked $event)
    {
        if ($event->user->id !== $event->post->user_id) {
            $event->post->user->notify(
                new UserLikedPost($event->user, $event->post)
            );
        }
    }
}
