<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserCommentedPost extends Notification
{
    use Queueable;

    protected $user;
    protected $post;
    protected $comment;

    public function __construct($user, $post, $comment)
    {
        $this->user = $user;
        $this->post = $post;
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'comment',
            'message' => "{$this->user->name} ha comentado en tu post",
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'comment_id' => $this->comment->id,
        ];
    }
}
