<?php

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
     * @param  mixed $data
     * @return void
     */
    public function sendNotification(User $user, string $notificationClass, $data = null)
    {
        if (! $user) {
            Log::error('User is null in sendNotification.');

            return;
        }

        logger()->info('Locale used in mail:', [
            'app_locale' => app()->getLocale(),
            'user_locale' => $user->locale,
        ]);

        // Send the notification
        $user->notify(new $notificationClass($data, $user));
    }
}
