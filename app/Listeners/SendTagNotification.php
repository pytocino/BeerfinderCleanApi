<?php

namespace App\Listeners;

use App\Events\UserTaggedInPost;
use App\Notifications\UserTaggedNotification;

class SendTagNotification
{
    public function handle(UserTaggedInPost $event)
    {
        // Solo enviar notificación si no es el mismo usuario
        if ($event->tagger->id !== $event->taggedUser->id) {
            $event->taggedUser->notify(
                new UserTaggedNotification($event->tagger, $event->post)
            );
        }
    }
}
