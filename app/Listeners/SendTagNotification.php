<?php

namespace App\Listeners;

use App\Events\UserTaggedInPost;
use App\Notifications\UserTaggedNotification;
use App\Services\NotificationDeduplicationService;

class SendTagNotification
{
    public function handle(UserTaggedInPost $event)
    {
        // Solo enviar notificación si no es el mismo usuario
        if ($event->tagger->id !== $event->taggedUser->id) {
            // Usar servicio de deduplicación
            if (!NotificationDeduplicationService::isDuplicate(
                'tag',
                $event->taggedUser->id,
                ['tagger_id' => $event->tagger->id, 'post_id' => $event->post->id]
            )) {
                $event->taggedUser->notify(
                    new UserTaggedNotification($event->tagger, $event->post)
                );
            }
        }
    }
}
