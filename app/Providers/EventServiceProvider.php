<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\PostLiked::class => [
            \App\Listeners\SendLikeNotification::class,
        ],
        \App\Events\UserFollowed::class => [
            \App\Listeners\SendFollowNotification::class,
        ],
        \App\Events\PostCommented::class => [
            \App\Listeners\SendCommentNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
