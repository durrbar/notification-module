<?php

namespace Modules\Notification\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send a notification to the user.
     *
     * @param mixed $user
     * @param string $notificationClass
     * @param mixed $data
     * @return void
     */
    public function sendNotification($user, string $notificationClass, $data = null)
    {
        if (!$user) {
            Log::error('User is null in sendNotification.');
            return;
        }

        // Ensure the notification class defines a NOTIFICATION_TYPE constant
        if (!defined("$notificationClass::NOTIFICATION_TYPE")) {
            Log::error("The notification class must define a NOTIFICATION_TYPE constant.");
            return;
        }

        // Determine the notification type
        $notificationType = $notificationClass::NOTIFICATION_TYPE;

        // Fetch or create notification preferences
        $preferences = $user->notificationPreferences()->firstOrCreate([
            'type' => $notificationType,
        ], [
            'email' => true,
            'sms' => false,
            'broadcast' => true,
        ]);

        // Instantiate the notification class
        $notification = new $notificationClass($data, $preferences);

        // Send the notification
        Notification::send($user, $notification);
    }
}
