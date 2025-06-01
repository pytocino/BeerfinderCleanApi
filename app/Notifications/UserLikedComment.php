<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserLikedComment extends Notification
{
    use Queueable;

    protected $liker;
    protected $comment;

    public function __construct($liker, $comment)
    {
        $this->liker = $liker;
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'comment_like',
            'message' => "{$this->liker->name} ha dado like a tu comentario",
            'user_id' => $this->liker->id,
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
        ];
    }
}
