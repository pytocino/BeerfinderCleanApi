<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use App\Notifications\UserFollowedNotification;
use App\Services\NotificationDeduplicationService;

class SendFollowNotification
{
    public function handle(UserFollowed $event)
    {
        if ($event->follower->id !== $event->followed->id) {
            // Usar servicio de deduplicación
            if (!NotificationDeduplicationService::isDuplicate(
                'follow',
                $event->followed->id,
                ['follower_id' => $event->follower->id]
            )) {
                $event->followed->notify(
                    new UserFollowedNotification($event->follower)
                );
            }
        }
    }
}
