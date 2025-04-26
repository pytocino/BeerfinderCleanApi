<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserLikedPost extends Notification
{
    use Queueable;

    protected $liker;
    protected $post;

    public function __construct($liker, $post)
    {
        $this->liker = $liker;
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'like',
            'message' => "{$this->liker->name} ha dado like a tu post",
            'user_id' => $this->liker->id,
            'post_id' => $this->post->id,
        ];
    }
}
