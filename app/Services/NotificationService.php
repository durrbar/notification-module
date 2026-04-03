<?php

declare(strict_types=1);

namespace Modules\Notification\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Modules\User\Models\User;

class NotificationService
{
    /**
     * Send a notification to the user.
     *
     * @param  mixed User $user
     * @param  mixed  $data
     */
    public function sendNotification(User $user, string $notificationClass, $data = null)
    {
        if (! $user) {
            Log::error('User is null in sendNotification.');

            return;
        }

        // Send the notification
        Notification::send($user, new $notificationClass($data, $user));
    }
}
