<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserTaggedNotification extends Notification
{
    use Queueable;

    protected $tagger;
    protected $post;

    public function __construct($tagger, $post)
    {
        $this->tagger = $tagger;
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'tag',
            'message' => "{$this->tagger->name} te ha etiquetado en un post",
            'user_id' => $this->tagger->id,
            'post_id' => $this->post->id,
        ];
    }
}
