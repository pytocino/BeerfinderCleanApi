<?php

namespace App\Listeners;

use App\Events\PostCommented;
use App\Notifications\UserCommentedPost;
use App\Services\NotificationDeduplicationService;

class SendCommentNotification
{
    public function handle(PostCommented $event)
    {
        if ($event->user->id !== $event->post->user_id) {
            // Usar servicio de deduplicación
            if (!NotificationDeduplicationService::isDuplicate(
                'comment',
                $event->post->user_id,
                ['commenter_id' => $event->user->id, 'post_id' => $event->post->id, 'comment_id' => $event->comment->id]
            )) {
                $event->post->user->notify(
                    new UserCommentedPost($event->user, $event->post, $event->comment)
                );
            }
        }
    }
}
