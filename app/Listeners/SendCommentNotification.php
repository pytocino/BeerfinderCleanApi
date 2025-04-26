<?php

namespace App\Listeners;

use App\Events\PostCommented;
use App\Notifications\UserCommentedPost;

class SendCommentNotification
{
    public function handle(PostCommented $event)
    {
        if ($event->user->id !== $event->post->user_id) {
            $event->post->user->notify(
                new UserCommentedPost($event->user, $event->post, $event->comment)
            );
        }
    }
}
