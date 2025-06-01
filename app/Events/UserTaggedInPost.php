<?php

namespace App\Events;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTaggedInPost
{
    use Dispatchable, SerializesModels;

    public $tagger;
    public $taggedUser;
    public $post;

    public function __construct(User $tagger, User $taggedUser, Post $post)
    {
        $this->tagger = $tagger;
        $this->taggedUser = $taggedUser;
        $this->post = $post;
    }
}
