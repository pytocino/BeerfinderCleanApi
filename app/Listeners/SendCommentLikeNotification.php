<?php

namespace App\Listeners;

use App\Events\CommentLiked;
use App\Notifications\UserLikedComment;

class SendCommentLikeNotification
{
    public function handle(CommentLiked $event)
    {
        // Solo enviar notificación si no es el mismo usuario
        if ($event->user->id !== $event->comment->user_id) {
            $event->comment->user->notify(
                new UserLikedComment($event->user, $event->comment)
            );
        }
    }
}
