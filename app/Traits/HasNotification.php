<?php

namespace Modules\Notification\Traits;

use Modules\Notification\Models\UserNotificationPreference;

trait HasNotification
{
    /**
     * Get the user's notification preferences.
     *
     * @return HasMany
     */
    public function notificationPreferences()
    {
        return $this->hasMany(UserNotificationPreference::class);
    }

    /**
     * Get the user's preference for a specific notification type.
     *
     * @param string $notificationType
     * @return \Modules\Notification\Models\UserNotificationPreference|null
     */
    public function getNotificationPreference(string $notificationType)
    {
        return $this->notificationPreferences()->where('notification_type', $notificationType)->first();
    }
}
