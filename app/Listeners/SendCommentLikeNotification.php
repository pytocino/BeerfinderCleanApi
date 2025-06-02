<?php

namespace App\Listeners;

use App\Events\CommentLiked;
use App\Notifications\UserLikedComment;
use App\Services\NotificationDeduplicationService;

class SendCommentLikeNotification
{
    public function handle(CommentLiked $event)
    {
        // Solo enviar notificación si no es el mismo usuario
        if ($event->user->id !== $event->comment->user_id) {
            // Usar servicio de deduplicación
            if (!NotificationDeduplicationService::isDuplicate(
                'comment_like',
                $event->comment->user_id,
                ['liker_id' => $event->user->id, 'comment_id' => $event->comment->id]
            )) {
                $event->comment->user->notify(
                    new UserLikedComment($event->user, $event->comment)
                );
            }
        }
    }
}
