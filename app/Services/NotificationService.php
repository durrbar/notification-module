<?php

namespace Modules\Notification\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send a notification to the user.
     *
     * @param  mixed  $user
     * @param  mixed  $data
     * @return void
     */
    public function sendNotification($user, string $notificationClass, $data = null)
    {
        if (! $user) {
            Log::error('User is null in sendNotification.');

            return;
        }

        // Send the notification
        Notification::send($user, new $notificationClass($data, $user));
    }
}
