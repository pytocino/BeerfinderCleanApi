<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use App\Notifications\UserFollowedNotification;

class SendFollowNotification
{
    public function handle(UserFollowed $event)
    {
        if ($event->follower->id !== $event->followed->id) {
            $event->followed->notify(
                new UserFollowedNotification($event->follower)
            );
        }
    }
}
