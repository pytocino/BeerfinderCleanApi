<?php

namespace App\Events;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentLiked
{
    use Dispatchable, SerializesModels;

    public $user;
    public $comment;

    public function __construct(User $user, Comment $comment)
    {
        $this->user = $user;
        $this->comment = $comment;
    }
}
